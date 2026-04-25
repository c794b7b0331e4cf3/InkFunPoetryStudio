<?php

namespace App\Services;

use App\Models\Poem;
use Illuminate\Support\Arr;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * 诗词
 *
 * @method Poem getModel()
 * @method Poem|\Illuminate\Database\Query\Builder query()
 */
class PoemService extends AdminService
{
    protected string $modelName = Poem::class;

    public function loadRelations($query)
    {
        $query->with(['user']);
    }

    public function list()
    {
        $data = parent::list();

        $data['items'] = Arr::map($data['items'], function ($item) {
            $data = $item->toArray();
            $data['source_type_label'] = $item->source_type->label();
            $data['display_status_label'] = $item->display_status->label();

            return $data;
        });

        return $data;
    }
}
