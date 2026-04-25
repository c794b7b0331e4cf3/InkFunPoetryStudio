<?php

namespace App\Services;

use App\Models\Suggestion;
use Slowlyo\OwlAdmin\Services\AdminService;

/**
 * 建议
 *
 * @method Suggestion getModel()
 * @method Suggestion|\Illuminate\Database\Query\Builder query()
 */
class SuggestionService extends AdminService
{
    protected string $modelName = Suggestion::class;
}
