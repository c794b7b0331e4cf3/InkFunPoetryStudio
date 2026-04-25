<?php

namespace App\Services;

use App\Models\UserBadgeRecord;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * 用户勋章记录
 *
 * @method UserBadgeRecord getModel()
 * @method UserBadgeRecord|\Illuminate\Database\Query\Builder query()
 */
class UserBadgeRecordService extends AdminService
{
    protected string $modelName = UserBadgeRecord::class;

    public function loadRelations($query)
    {
        $query->with(['user']);
    }
}
