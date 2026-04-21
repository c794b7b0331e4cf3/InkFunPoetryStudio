<?php

namespace App\Http\Services;

use App\Models\PoemImage;
use App\Models\User;
use App\Models\UserPoemImageLikeRecord;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BaiLianService
{
    /**
     * @throws ConnectionException
     */
    protected function textGenerate(string $model, array $extra, string $instruction, string $prompt)
    {
        return json_decode(Http::asJson()
            ->withHeaders([
                'Authorization' => 'Bearer '.config('services.bailian.api_key'),
            ])
            ->post('https://dashscope.aliyuncs.com/compatible-mode/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $instruction,
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'response_format' => [
                    'type' => 'json_object',
                ],

                ...$extra,
            ])->json('choices.0.message.content'), true);
    }

    /**
     * @throws ConnectionException
     */
    protected function textTalk(string $model, array $extra, string $instruction, string $prompt, array $histories = [])
    {
        return json_decode(Http::asJson()
            ->withHeaders([
                'Authorization' => 'Bearer '.config('services.bailian.api_key'),
            ])
            ->post('https://dashscope.aliyuncs.com/compatible-mode/v1/chat/completions', [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $instruction,
                    ],
                    ...$histories,
                    [
                        'role' => 'user',
                        'content' => $prompt,
                    ],
                ],
                'response_format' => [
                    'type' => 'json_object',
                ],

                ...$extra,
            ])->json('choices.0.message.content'), true);
    }

    /**
     * @throws ConnectionException
     */
    protected function imageGenerate(string $model, array $parameters, string $prompt)
    {

        return Http::asJson()
            ->withHeaders([
                'Authorization' => 'Bearer '.config('services.bailian.api_key'),
            ])
            ->timeout(600)
            ->post('https://dashscope.aliyuncs.com/api/v1/services/aigc/multimodal-generation/generation', [
                'model' => $model,
                'input' => [
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => [
                                [
                                    'text' => $prompt,
                                ],
                            ],
                        ],
                    ],
                ],
                'parameters' => $parameters,
            ])->json('output.choices.0.message.content.0.image');
    }

    /**
     * @throws ConnectionException
     */
    protected function visualGenerate(string $model, string $instruction, string $imageRaw, string $prompt)
    {
        return json_decode(Http::asJson()
            ->withHeaders([
                'Authorization' => 'Bearer '.config('services.bailian.api_key'),
            ])
            ->post('https://dashscope.aliyuncs.com/api/v1/services/aigc/multimodal-generation/generation', [
                'model' => $model,
                'input' => [
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $instruction,
                        ],
                        [
                            'role' => 'user',
                            'content' => [
                                [
                                    'image' => 'data:image/webp;base64,'.base64_encode($imageRaw),
                                ],
                                [
                                    'text' => $prompt,
                                ],
                            ],
                        ],
                    ],
                ],
            ])->json('output.choices.0.message.content.0.text'), true);
    }

    public function summarize(User $user)
    {
        $likes = UserPoemImageLikeRecord::query()
            ->with(['poemImage', 'poemImage.poem'])
            ->where('user_id', $user->id)
            ->get();

        $generated = PoemImage::query()
            ->with(['poem'])
            ->where('user_id', $user->id)
            ->get();

        $prompt = implode("\n", [
            '用户收藏的诗词: ',
            $likes->map(function (UserPoemImageLikeRecord $item) {
                if ($item->poemImage === null || $item->poemImage->poem === null) {
                    return null;
                }

                return $item->poemImage->poem->content;
            })->filter(function ($value) {
                return $value !== null;
            })->join(', '),

            '用户生成的诗词: ',
            $generated->map(function (PoemImage $item) {
                if ($item->poem === null) {
                    return null;
                }

                return $item->poem->content;
            })->filter(function ($value) {
                return $value !== null;
            })->join(', '),
        ]);

        try {
            $data = $this->textGenerate(config('services.bailian.text_model.name'), config('services.bailian.text_model.extra', []), implode("\n", [
                config('services.bailian.prompts.summarize'),
                '返回格式为 JSON, 格式为: { "summarize": string }',
            ]), $prompt);

            if (empty($data['summarize'])) {
                return null;
            }

            return $data;
        } catch (ConnectionException $e) {
            Log::error('总结生成异常', [
                'exception' => $e,
            ]);

            return null;
        }
    }

    public function poemToImage(string $prompt)
    {
        try {
            $data = $this->imageGenerate(
                config('services.bailian.image_model.name'),
                config('services.bailian.image_model.parameters', []),
                implode("\n", [
                    config('services.bailian.prompts.poem_to_image'),
                    $prompt,
                ])
            );

            if (empty($data)) {
                return null;
            }

            return $data;
        } catch (ConnectionException $e) {
            Log::error('诗词图片生成异常', [
                'exception' => $e,
            ]);

            return null;
        }
    }

    public function characterTalk(string $character, string $prompt, array $histories = [])
    {
        try {
            $messages = [];

            foreach ($histories as $index => $history) {
                $messages[] = [
                    'role' => $index % 2 === 0 ? 'user' : 'assistant',
                    'content' => $history,
                ];
            }

            $data = Arr::first(config('services.bailian.characters'), function (array $data) use ($character) {
                return $data['name'] === $character;
            });

            if ($data === null) {
                return null;
            }

            $data = $this->textTalk(config('services.bailian.text_model.name'), config('services.bailian.text_model.extra', []), implode("\n", [
                $data['prompt'],
                '返回格式为 JSON, 格式为: { "text": string }',
            ]), $prompt, $messages);

            if (empty($data['text'])) {
                return null;
            }

            return $data;
        } catch (ConnectionException $e) {
            Log::error('对话生成异常', [
                'exception' => $e,
            ]);

            return null;
        }
    }

    public function poeticChain(array $keywords, string $prompt, array $histories = [])
    {
        try {
            $messages = [];

            foreach ($histories as $index => $history) {
                if ($history === null) {
                    continue;
                }

                $messages[] = [
                    'role' => $index % 2 === 0 ? 'user' : 'assistant',
                    'content' => $history,
                ];
            }

            $data = $this->textTalk(config('services.bailian.text_model.name'), config('services.bailian.text_model.extra', []), implode("\n", [
                config('services.bailian.prompts.poetic_chain'),
                '关键词: '.implode(', ', $keywords),
                '返回格式为 JSON, 格式为: { "poem": string }',
            ]), $prompt, $messages);

            if (empty($data['poem'])) {
                return null;
            }

            return $data;
        } catch (ConnectionException $e) {
            Log::error('飞花令生成异常', [
                'exception' => $e,
            ]);

            return null;
        }
    }

    public function poemCouplet(string $prompt, array $histories = [])
    {
        try {
            $messages = [];

            foreach ($histories as $index => $history) {
                $messages[] = [
                    'role' => $index % 2 === 0 ? 'user' : 'assistant',
                    'content' => $history,
                ];
            }

            $data = $this->textTalk(config('services.bailian.text_model.name'), config('services.bailian.text_model.extra', []), implode("\n", [
                config('services.bailian.prompts.poem_couplet'),
                '返回格式为 JSON, 格式为: { "poem": string }',
            ]), $prompt, $messages);

            if (empty($data['poem'])) {
                return null;
            }

            return $data;
        } catch (ConnectionException $e) {
            Log::error('对诗生成异常', [
                'exception' => $e,
            ]);

            return null;
        }
    }

    public function poemFromImage(string $imageRaw)
    {
        try {
            $data = $this->visualGenerate(config('services.bailian.visual_model.name'), implode("\n", [
                config('services.bailian.prompts.poem_from_image'),
                '返回格式为 JSON, 格式为: { "poem": string, "title": string, "tags": string[] }',
            ]), $imageRaw, '创作一首诗');

            if (
                empty($data['poem']) ||
                ! isset($data['title']) ||
                ! isset($data['tags']) ||
                ! is_array($data['tags'])
            ) {
                return null;
            }

            return $data;
        } catch (ConnectionException $e) {
            Log::error('图生诗生成异常', [
                'exception' => $e,
            ]);

            return null;
        }
    }

    public function poemSuggest(string $prompt)
    {
        try {
            $data = $this->textGenerate(config('services.bailian.text_model.name'), config('services.bailian.text_model.extra', []), implode("\n", [
                config('services.bailian.prompts.poem_suggest'),
                '返回格式为 JSON, 格式为: { "suggest": string }',
            ]), $prompt);

            if (empty($data['suggest'])) {
                return null;
            }

            return $data;
        } catch (ConnectionException $e) {
            Log::error('诗词建议生成异常', [
                'exception' => $e,
            ]);

            return null;
        }
    }

    public function poemValidate(string $prompt)
    {
        try {
            $data = $this->textGenerate(config('services.bailian.text_model.name'), config('services.bailian.text_model.extra', []), implode("\n", [
                config('services.bailian.prompts.poem_validate'),
                '返回格式为 JSON, 格式为: { "is_valid": boolean, "error": string }',
            ]), $prompt);

            if (! isset($data['is_valid']) && ! isset($data['error'])) {
                return null;
            }

            return $data;
        } catch (ConnectionException $e) {
            Log::error('诗词校验生成异常', [
                'exception' => $e,
            ]);

            return null;
        }
    }
}
