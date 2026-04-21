<?php

namespace App\Enums;

use App\Enums\Attributes\Label;
use App\Enums\Traits\EnumTrait;

enum DisplayStatuses: int
{
    use EnumTrait;

    #[Label('公开')]
    case PUBLIC = 1;

    #[Label('私密')]
    case PRIVATE = 2;
}
