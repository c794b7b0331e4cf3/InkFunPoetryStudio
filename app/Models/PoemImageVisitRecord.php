<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PoemImageVisitRecord extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function poemImage(): BelongsTo
    {
        return $this->belongsTo(PoemImage::class);
    }
}
