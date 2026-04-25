<?php

namespace App\Services;

use App\Models\UserPoemHistoryRecord;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * 用户诗词历史记录
 *
 * @method UserPoemHistoryRecord getModel()
 * @method UserPoemHistoryRecord|\Illuminate\Database\Query\Builder query()
 */
class UserPoemHistoryRecordService extends AdminService
{
    protected string $modelName = UserPoemHistoryRecord::class;

    public function loadRelations($query)
    {
        $query->with(['user', 'poem']);
    }
}
