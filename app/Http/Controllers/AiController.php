<?php

namespace App\Http\Controllers;

use App\Enums\DisplayStatuses;
use App\Enums\FileTypes;
use App\Enums\PoemSourceTypes;
use App\Http\Requests\AiCharacterTalkRequest;
use App\Http\Requests\AiCoupletRequest;
use App\Http\Requests\AiImageGenerateRequest;
use App\Http\Requests\AiImageToPoemRequest;
use App\Http\Requests\AiPoeticChainRequest;
use App\Http\Requests\AiSuggestRequest;
use App\Http\Resources\PoemImageResource;
use App\Http\Services\BaiLianService;
use App\Http\Services\FileService;
use App\Http\Services\GreetingService;
use App\Http\Services\InertiaMessageService;
use App\Models\Poem;
use App\Models\UserBadgeRecord;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AiController
{
    public function __construct(
        protected BaiLianService $baiLianService
    ) {}

    public function renderImageGenerate(array $extra = [])
    {
        return Inertia::render('ai/image-generate', [
            'greeting' => GreetingService::generate(),

            ...$extra,
        ]);
    }

    public function imageGenerate(AiImageGenerateRequest $request)
    {
        $data = $request->validated();

        $response = $this->baiLianService->poemValidate($data['input']);

        if ($response === null) {
            InertiaMessageService::error('生成出错');

            return back();
        }

        if (isset($response['is_valid']) && ! $response['is_valid']) {
            if (isset($response['error'])) {
                InertiaMessageService::error($response['error']);
            } else {
                InertiaMessageService::error('校验出错');
            }

            return back();
        }

        $poem = Poem::firstOrCreate([
            'content' => $data['input'],
        ], [
            'user_id' => Auth::id(),
            'title' => null,
            'author' => null,
            'dynasty' => null,
            'source_type' => PoemSourceTypes::USER_GENERATED,
            'display_status' => DisplayStatuses::PUBLIC,
        ]);

        try {
            $url = $this->baiLianService->poemToImage($data['input']);

            if ($url === null) {
                InertiaMessageService::error('生成出错');

                return back();
            }

            $response = Http::get($url);

            $file = FileService::process(
                raw: $response->body(),
                storeName: Str::ulid()
                    ->toString().'.png',
                mimetype: $response->header('Content-Type'),
                type: FileTypes::IMAGE,
                metadata: [
                    'width' => 2048,
                    'height' => 1152,
                ]
            );

            $image = $poem->images()
                ->create([
                    'user_id' => Auth::id(),
                    'file_id' => $file->id,
                ]);

            $image->load(['poem', 'poem.tags', 'poem.user', 'file']);
            $image->loadCount('likes');

            return $this->renderImageGenerate([
                'generated' => new PoemImageResource($image),
            ]);
        } catch (ConnectionException $e) {
            Log::error('图片下载出错', [
                'exception' => $e,
            ]);

            InertiaMessageService::error('生成出错');
        }

        return back();
    }

    public function renderCouplet(array $extra = [])
    {
        return Inertia::render('ai/couplet', [
            'history' => $extra['history'] ?? [],
            'greeting' => GreetingService::generate(),

            ...$extra,
        ]);
    }

    public function couplet(AiCoupletRequest $request)
    {
        $data = $request->validated();

        $response = $this->baiLianService->poemCouplet($data['input'], $data['history']);

        if ($response === null) {
            InertiaMessageService::error('生成出错');

            return back();
        }

        return $this->renderCouplet([
            'history' => [
                ...$data['history'],

                $data['input'],
                $response['poem'],
            ],
        ]);
    }

    public function renderSuggest(array $extra = [])
    {
        return Inertia::render('ai/suggest', [
            'greeting' => GreetingService::generate(),

            ...$extra,
        ]);
    }

    public function suggest(AiSuggestRequest $request)
    {
        $data = $request->validated();

        $response = $this->baiLianService->poemSuggest($data['input']);

        if ($response === null) {
            InertiaMessageService::error('生成出错');

            return back();
        }

        return $this->renderSuggest([
            'generated' => $response,
        ]);
    }

    public function renderImageToPoem(array $extra = [])
    {
        return Inertia::render('ai/image-to-poem', [
            'greeting' => GreetingService::generate(),

            ...$extra,
        ]);
    }

    public function imageToPoem(AiImageToPoemRequest $request)
    {
        $data = $request->validated();

        /** @var UploadedFile $file */
        $file = $data['input'];

        try {
            $response = $this->baiLianService->poemFromImage(
                $file->get()
            );
        } catch (FileNotFoundException) {
            InertiaMessageService::error('文件不存在');

            return back();
        }

        if ($response === null) {
            InertiaMessageService::error('生成出错');

            return back();
        }

        $poem = Poem::firstOrCreate([
            'title' => $response['title'],
            'content' => $response['poem'],
        ], [
            'user_id' => Auth::id(),
            'author' => null,
            'dynasty' => null,
            'source_type' => PoemSourceTypes::FROM_IMAGE,
            'display_status' => DisplayStatuses::PUBLIC,
        ]);

        foreach ($response['tags'] as $tag) {
            $poem->tags()
                ->firstOrCreate([
                    'name' => $tag,
                ]);
        }

        try {
            $file = FileService::process(
                raw: $file->get(),
                storeName: Str::ulid()
                    ->toString().'.png',
                mimetype: $file->getMimeType() ?? 'application/octet-stream',
                type: FileTypes::IMAGE
            );
        } catch (FileNotFoundException) {
            InertiaMessageService::error('文件出错');

            return back();
        }

        $image = $poem->images()
            ->create([
                'user_id' => Auth::id(),
                'file_id' => $file->id,
            ]);

        $image->load(['poem', 'poem.tags', 'poem.user', 'file']);
        $image->loadCount('likes');

        return $this->renderImageToPoem([
            'generated' => new PoemImageResource($image),
        ]);
    }

    public function renderCharacterTalk(array $extra = [])
    {
        $badges = [];

        if (isset($extra['character'])) {
            if (isset($extra['history']) && count($extra['history']) >= config('services.bailian.character_soulmate_minimum_talk_count', 10) * 2) {
                UserBadgeRecord::query()
                    ->firstOrCreate([
                        'user_id' => Auth::id(),
                        'key' => $extra['character'].'.soulmate',
                        'is_new' => true,
                    ]);
            }

            $character = Arr::first(config('services.bailian.characters'), function (array $data) use ($extra) {
                return $data['name'] === $extra['character'];
            });

            if ($character === null) {
                InertiaMessageService::error('诗人不存在');

                return back();
            }

            foreach ($character['badges'] as $name => $badge) {
                $label = __('badges.'.$name);

                $query = UserBadgeRecord::query()
                    ->where([
                        'user_id' => Auth::id(),
                        'key' => $character['name'].'.'.$name,
                    ]);

                $badges[$character['name'].' - '.$label] = [
                    'image' => $badge,
                    'archived' => $query->exists(),
                    'is_new' => $query->value('is_new'),
                ];

                $query->update([
                    'is_new' => false,
                ]);
            }
        }

        return Inertia::render('ai/character_talk', [
            'badges' => $badges,
            'characters' => empty($extra['character']) ? Arr::map(config('services.bailian.characters'), function (array $data) {
                return $data['name'];
            }) : [],
            'character' => $extra['character'] ?? '',

            'history' => $extra['history'] ?? [],
            'greeting' => GreetingService::generate(),

            ...$extra,
        ]);
    }

    public function characterTalk(AiCharacterTalkRequest $request)
    {
        $data = $request->validated();

        $response = $this->baiLianService->characterTalk($data['character'], $data['input'], $data['history']);

        if ($response === null) {
            InertiaMessageService::error('生成出错');

            return back();
        }

        return $this->renderCharacterTalk([
            'character' => $data['character'],
            'history' => [
                ...$data['history'],

                $data['input'],
                $response['text'],
            ],
        ]);
    }

    public function renderPoeticChain(array $extra = [])
    {
        return Inertia::render('ai/poetic_chain', [
            'keywords' => $extra['keywords'] ?? [],
            'history' => $extra['history'] ?? [],
            'greeting' => GreetingService::generate(),

            ...$extra,
        ]);
    }

    public function poeticChain(AiPoeticChainRequest $request)
    {
        $data = $request->validated();

        if (! empty($data['history']) && empty($data['input'])) {
            InertiaMessageService::error('请输入内容');

            return back();
        }

        $response = $this->baiLianService->poeticChain($data['keywords'], $data['input'] ?? '', $data['history']);

        if ($response === null) {
            InertiaMessageService::error('生成出错');

            return back();
        }

        return $this->renderPoeticChain([
            'keywords' => $data['keywords'],

            'history' => [
                ...$data['history'],

                $data['input'],
                $response['poem'],
            ],
        ]);
    }
}
