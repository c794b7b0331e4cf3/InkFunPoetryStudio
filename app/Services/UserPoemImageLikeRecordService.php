<?php

namespace App\Services;

use App\Models\UserPoemImageLikeRecord;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * 用户诗词收藏记录
 *
 * @method UserPoemImageLikeRecord getModel()
 * @method UserPoemImageLikeRecord|\Illuminate\Database\Query\Builder query()
 */
class UserPoemImageLikeRecordService extends AdminService
{
    protected string $modelName = UserPoemImageLikeRecord::class;

    public function loadRelations($query)
    {
        $query->with(['user', 'poemImage']);
    }
}
