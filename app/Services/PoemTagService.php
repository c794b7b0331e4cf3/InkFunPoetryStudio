<?php

namespace App\Services;

use App\Models\PoemTag;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * 诗词标签
 *
 * @method PoemTag getModel()
 * @method PoemTag|\Illuminate\Database\Query\Builder query()
 */
class PoemTagService extends AdminService
{
    protected string $modelName = PoemTag::class;
}
