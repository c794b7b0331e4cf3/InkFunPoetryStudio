<?php

namespace App\Services;

use App\Models\PoemImageVisitRecord;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * 诗词图片访问记录
 *
 * @method PoemImageVisitRecord getModel()
 * @method PoemImageVisitRecord|\Illuminate\Database\Query\Builder query()
 */
class PoemImageVisitRecordService extends AdminService
{
    protected string $modelName = PoemImageVisitRecord::class;
}
