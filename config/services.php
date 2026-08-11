<?php

use Illuminate\Support\Arr;

return [
    'siliconflow' => [
        'base_url' => env('SILICONFLOW_BASE_URL'),
        'api_key' => env('SILICONFLOW_API_KEY'),
    ],
    'bailian' => [
        'base_url' => env('BAILIAN_BASE_URL'),
        'api_key' => env('BAILIAN_API_KEY'),

        'text_model' => [
            'name' => env('BAILIAN_TEXT_MODEL_NAME', 'deepseek-v4-pro'),
            'extra' => [
                'enable_thinking' => false,
            ],
        ],

        'image_model' => [
            'name' => env('BAILIAN_IMAGE_MODEL_NAME', 'qwen-image-3.0-pro'),
            'parameters' => [
                'negative_prompt' => '文字, 水印, 签名, 标志, 用户名, 多余肢体, 畸形手部, 手指融合, 解剖结构错误, 毁容, 面部崩坏, 模糊, 低画质, 最差画质, 压缩伪影, 3D渲染, 电脑CG, 动漫风格, 油画厚涂, 赛博朋克, 现代建筑, 西方建筑, 日式风格, 韩式服饰, 樱花, 鸟居, 现代服饰, 现代眼镜, 玻璃材质, 电子屏幕, 霓虹灯, 电线杆, 汽车, 飞机, 曝光过度, 杂乱背景, 繁复纹理, 塑料质感, 虚假反光, 画面拥挤, 破坏留白',
                'prompt_extend' => true,
                'size' => '2048*1152',
            ],
        ],

        'visual_model' => [
            'name' => env('BAILIAN_VISUAL_MODEL_NAME', 'qwen3.8-max'),
        ],

        'character_soulmate_minimum_talk_count' => 10,

        'characters' => Arr::map([
            [
                'name' => '李白',
                'badges' => [
                    'soulmate' => '/characters/badges/soulmate/dc8c1209ae4131ad249f020b538f4b182fee808c6ea3e1078f9376638548d69c.webp',
                ],
            ],
            [
                'name' => '杜甫',
                'badges' => [
                    'soulmate' => '/characters/badges/soulmate/d986e9c0ef1917520da97f7383a1a78f3c1b863e9dfdc92b5b7cec987ba10a1e.webp',
                ],
            ],
            [
                'name' => '苏轼',
                'badges' => [
                    'soulmate' => '/characters/badges/soulmate/a3cd6faee4e1b819bd023b1421c8b7ca8495544648c5ed126ce558f4b8a9c147.webp',
                ],
            ],
            [
                'name' => '辛弃疾',
                'badges' => [
                    'soulmate' => '/characters/badges/soulmate/ce94f3676ea22c4c2357c89c9c81623c35c15ed0e7b47dfee01d440783c583ef.webp',
                ],
            ],
            [
                'name' => '李清照',
                'badges' => [
                    'soulmate' => '/characters/badges/soulmate/9702b086171ba607690decfcc3e1cfe87ea866150e6bf389196b9981b42c6026.webp',
                ],
            ],
            [
                'name' => '王维',
                'badges' => [
                    'soulmate' => '/characters/badges/soulmate/bf8a7426edb3badb4fbcb04bebfc3ca71cc0ad3ad622dd05cc554b31f9a7ddaa.webp',
                ],
            ],
            [
                'name' => '白居易',
                'badges' => [
                    'soulmate' => '/characters/badges/soulmate/77b699c4bf977b2ed30c499822a6f350b3db470a103fe96b2b0c7ce513479fe3.webp',
                ],
            ],
            [
                'name' => '杜牧',
                'badges' => [
                    'soulmate' => '/characters/badges/soulmate/2a1ba4a3258992ac5d0dfa47234caae8d723517f276cfb3b2774d0790f79f793.webp',
                ],
            ],
            [
                'name' => '李商隐',
                'badges' => [
                    'soulmate' => '/characters/badges/soulmate/2ba8e748761e7e329423014de7dbbe02c5195695d56313986610973fba3e61a4.webp',
                ],
            ],
            [
                'name' => '王昌龄',
                'badges' => [
                    'soulmate' => '/characters/badges/soulmate/a93c7c9c7ed45e6c644067956fbadf6179fc672db0f1e6165ab6d7007793e821.webp',
                ],
            ],
            [
                'name' => '孟浩然',
                'badges' => [
                    'soulmate' => '/characters/badges/soulmate/32d444f120993752cb9a106b182c91c8cb18e91262813d0b40f6ededed387747.webp',
                ],
            ],
            [
                'name' => '贾岛',
                'badges' => [
                    'soulmate' => '/characters/badges/soulmate/0c9dce89da496fb7c1b4eb38a083ad39c56548d63a9e817bae7a89429e0ad035.webp',
                ],
            ],
            [
                'name' => '刘禹锡',
                'badges' => [
                    'soulmate' => '/characters/badges/soulmate/8c82c2019965e9fdb3fec7495864436990f219fea9549a183f595a163c5da593.webp',
                ],
            ],
            [
                'name' => '韩愈',
                'badges' => [
                    'soulmate' => '/characters/badges/soulmate/7dc7f5e44d3daff73a8ff0fb640f6a19bbff5bf64400611f0b4956096fd998ee.webp',
                ],
            ],
            [
                'name' => '柳宗元',
                'badges' => [
                    'soulmate' => '/characters/badges/soulmate/a62a32fc046954b2f784f63546c65781122b01d62d8d0fcf220641ad6e428b10.webp',
                ],
            ],
            [
                'name' => '王之涣',
                'badges' => [
                    'soulmate' => '/characters/badges/soulmate/d988549eb40da089da17ac159fbec6161c4fb58452f6de23cec061e185e3ef57.webp',
                ],
            ],
            [
                'name' => '岑参',
                'badges' => [
                    'soulmate' => '/characters/badges/soulmate/a2cbcab25d0bc460f55cc16d8752156aab45bcc94b1b975eab6440abc7fb3c5f.webp',
                ],
            ],
            [
                'name' => '高适',
                'badges' => [
                    'soulmate' => '/characters/badges/soulmate/9c4969aa92fa8d2c08bfd8031627474d0994fca329ecc201d32468b1b285e13a.webp',
                ],
            ],
        ], function (array $item) {
            $item['prompt'] = 'prompts/character.md'
                    |> resource_path(...)
                    |> file_get_contents(...)
                    |> (function (string $prompt) use ($item) {
                        return str_replace('{name}', $item['name'], $prompt);
                    });

            return $item;
        }),

        'prompts' => [
            'poetic_chain' => file_get_contents(
                resource_path('prompts/poetic_chain.md')
            ),
            'poem_from_image' => file_get_contents(
                resource_path('prompts/poem_from_image.md')
            ),
            'poem_validate' => file_get_contents(
                resource_path('prompts/poem_validate.md')
            ),
            'poem_suggest' => file_get_contents(
                resource_path('prompts/poem_suggest.md')
            ),
            'poem_couplet' => file_get_contents(
                resource_path('prompts/poem_couplet.md')
            ),
            'poem_to_image' => file_get_contents(
                resource_path('prompts/poem_to_image.md')
            ),
            'summarize' => file_get_contents(
                resource_path('prompts/summarize.md')
            ),
        ],
    ],
];
