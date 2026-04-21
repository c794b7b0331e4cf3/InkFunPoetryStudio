<?php

namespace Database\Seeders;

use App\Enums\DisplayStatuses;
use App\Enums\FileTypes;
use App\Enums\PoemSourceTypes;
use App\Models\File;
use App\Models\Poem;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $poem = Poem::create([
            'title' => '临江仙',
            'author' => '杨慎',
            'dynasty' => '明',
            'content' => implode("\n", [
                '滚滚长江东逝水，浪花淘尽英雄。是非成败转头空。',
                '青山依旧在，几度夕阳红。',
                '白发渔樵江渚上，惯看秋月春风。',
                '一壶浊酒喜相逢。古今多少事，都付笑谈中。',
            ]),
            'source_type' => PoemSourceTypes::EXISTING,
            'display_status' => DisplayStatuses::PUBLIC,
        ]);

        $poem->images()
            ->create([
                'file_id' => File::create([
                    'disk' => 'local',
                    'path' => 'ljx.png',
                    'original_filename' => '-',
                    'mimetype' => 'image/png',
                    'size' => -1,
                    'ip' => '-',
                    'type' => FileTypes::IMAGE,
                    'metadata' => [
                        'width' => 2048,
                        'height' => 1152,
                    ],
                    'hash' => '-',
                ])->id,

                'prompt' => $poem->content,
            ]);

        $poem2 = Poem::create([
            'title' => '念奴娇·赤壁怀古',
            'author' => '苏轼',
            'dynasty' => '宋',
            'content' => implode("\n", [
                '大江东去，浪淘尽，千古风流人物。',
                '故垒西边，人道是，三国周郎赤壁。',
                '乱石穿空，惊涛拍岸，卷起千堆雪。',
                '江山如画，一时多少豪杰。',
                '遥想公瑾当年，小乔初嫁了，雄姿英发。',
                '羽扇纶巾，谈笑间，樯橹灰飞烟灭。',
                '故国神游，多情应笑我，早生华发。',
                '人生如梦，一尊还酹江月。',
            ]),
            'source_type' => PoemSourceTypes::EXISTING,
            'display_status' => DisplayStatuses::PUBLIC,
        ]);

        $poem2->images()
            ->create([
                'file_id' => File::create([
                    'disk' => 'local',
                    'path' => 'nnq.png',
                    'original_filename' => '-',
                    'mimetype' => 'image/png',
                    'size' => -1,
                    'ip' => '-',
                    'type' => FileTypes::IMAGE,
                    'metadata' => [
                        'width' => 2048,
                        'height' => 1152,
                    ],
                    'hash' => '-',
                ])->id,

                'prompt' => $poem->content,
            ]);
    }
}
