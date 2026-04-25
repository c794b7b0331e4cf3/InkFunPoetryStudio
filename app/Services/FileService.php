<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Support\Arr;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * 文件
 *
 * @method File getModel()
 * @method File|\Illuminate\Database\Query\Builder query()
 */
class FileService extends AdminService
{
    protected string $modelName = File::class;

    public function loadRelations($query)
    {
        $query->with(['user']);
    }

    public function list()
    {
        $data = parent::list();

        $data['items'] = Arr::map($data['items'], function ($item) {
            $data = $item->toArray();
            $data['type_label'] = $item->type->label();

            return $data;
        });

        return $data;
    }
}
