<?php

namespace App\Services;

use App\Models\PoemImage;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\URL;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * 诗词图片
 *
 * @method PoemImage getModel()
 * @method PoemImage|\Illuminate\Database\Query\Builder query()
 */
class PoemImageService extends AdminService
{
    protected string $modelName = PoemImage::class;

    public function loadRelations($query)
    {
        $query->with(['user', 'poem', 'file']);
    }

    public function list()
    {
        $data = parent::list();

        $data['items'] = Arr::map($data['items'], function ($item) {
            $data = $item->toArray();

            $data['file']['download_url'] = URL::signedRoute(
                'file.download',
                [
                    'file' => $data['file']['id'],
                ]
            );

            return $data;
        });

        return $data;
    }
}
