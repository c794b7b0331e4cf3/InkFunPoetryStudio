<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PoemTagAssign extends Model
{
    public function tag(): BelongsTo
    {
        return $this->belongsTo(PoemTag::class, 'poem_tag_id');
    }

    public function poem(): BelongsTo
    {
        return $this->belongsTo(Poem::class);
    }
}
