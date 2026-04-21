<?php

namespace App\Http\Controllers;

use App\Http\Resources\PoemImageResource;
use App\Http\Resources\PoemResource;
use App\Http\Services\BaiLianService;
use App\Http\Services\InertiaMessageService;
use App\Models\Poem;
use App\Models\PoemImage;
use App\Models\UserBadgeRecord;
use App\Models\UserPoemHistoryRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileController
{
    public function __construct(
        protected BaiLianService $baiLianService
    ) {}

    public function renderSummarize()
    {
        $user = Auth::user();
        $generated = $this->baiLianService->summarize($user);

        if ($generated === null) {
            InertiaMessageService::error('总结失败');

            return back();
        }

        return Inertia::render('summarize', [
            'generated' => $generated,
        ]);
    }

    public function render()
    {
        $user = Auth::user();

        return Inertia::render('profile', [
            'user' => $user,

            'badges' => function () use ($user) {
                $result = [];

                foreach (config('services.bailian.characters', []) as $character) {
                    foreach ($character['badges'] as $name => $badge) {
                        $label = __('badges.'.$name);

                        $result[$character['name'].' - '.$label] = [
                            'image' => $badge,
                            'archived' => UserBadgeRecord::query()
                                ->where([
                                    'user_id' => $user->id,
                                    'key' => $character['name'].'.'.$name,
                                ])
                                ->exists(),
                        ];
                    }
                }

                return $result;
            },
            'histories' => Inertia::optional(function () use ($user) {
                return UserPoemHistoryRecord::query()
                    ->with(['poem'])
                    ->latest()
                    ->where('user_id', $user->id)
                    ->paginate(pageName: 'histories_page');
            }),
            'poems' => Inertia::optional(function () use ($user) {
                return PoemResource::collection(
                    Poem::query()
                        ->latest()
                        ->where('user_id', $user->id)
                        ->paginate(pageName: 'poems_page')
                );
            }),
            'likes' => Inertia::optional(function () use ($user) {
                return PoemImageResource::collection(
                    PoemImage::query()
                        ->latest()
                        ->with(['poem', 'file'])
                        ->whereHas('likes.user', function (Builder $query) use ($user) {
                            $query->where('user_id', $user->id);
                        })
                        ->paginate(pageName: 'generated_page')
                );
            }),
            'generated' => Inertia::optional(function () use ($user) {
                return PoemImageResource::collection(
                    PoemImage::query()
                        ->latest()
                        ->with(['poem', 'file'])
                        ->withCount(['likes'])
                        ->where('user_id', $user->id)
                        ->paginate(pageName: 'generated_page')
                );
            }),
        ]);
    }
}
