<?php

namespace App\Services;

use App\Models\PoemImage;
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
}
