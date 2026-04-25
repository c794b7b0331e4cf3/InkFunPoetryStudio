<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PoemImage extends Model
{
    protected $fillable = ['user_id', 'file_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function poem(): BelongsTo
    {
        return $this->belongsTo(Poem::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(UserPoemImageLikeRecord::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PoemImageComment::class);
    }
}
