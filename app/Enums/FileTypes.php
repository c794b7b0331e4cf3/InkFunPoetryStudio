<?php

namespace App\Enums;

use App\Enums\Attributes\Label;
use App\Enums\Traits\EnumTrait;

enum FileTypes: int
{
    use EnumTrait;

    #[Label('未知')]
    case UNKNOWN = 1;

    #[Label('图片')]
    case IMAGE = 2;
}
