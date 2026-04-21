<?php

namespace App\Enums;

use App\Enums\Attributes\Label;
use App\Enums\Traits\EnumTrait;

enum PoemSourceTypes: int
{
    use EnumTrait;

    #[Label('原有诗词')]
    case EXISTING = 0;

    #[Label('用户生成')]
    case USER_GENERATED = 1;

    #[Label('对话生成')]
    case DIALOGUE = 2;

    #[Label('图片生成')]
    case FROM_IMAGE = 3;
}
