<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PoemImageComment extends Model
{
    protected $fillable = ['parent_id', 'poem_image_id', 'user_id', 'content'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(PoemImageComment::class, 'parent_id');
    }

    public function poemImage(): BelongsTo
    {
        return $this->belongsTo(PoemImage::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function children(): HasMany|PoemImageComment
    {
        return $this->hasMany(PoemImageComment::class, 'parent_id');
    }
}
