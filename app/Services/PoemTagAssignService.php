<?php

namespace App\Services;

use App\Models\PoemTagAssign;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * 诗词标签分配
 *
 * @method PoemTagAssign getModel()
 * @method PoemTagAssign|\Illuminate\Database\Query\Builder query()
 */
class PoemTagAssignService extends AdminService
{
    protected string $modelName = PoemTagAssign::class;

    public function loadRelations($query)
    {
        $query->with(['poem', 'tag']);
    }
}
