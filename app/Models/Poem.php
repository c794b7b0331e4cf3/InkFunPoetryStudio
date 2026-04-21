<?php

namespace App\Models;

use App\Enums\DisplayStatuses;
use App\Enums\PoemSourceTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poem extends Model
{
    protected $fillable = ['user_id', 'title', 'author', 'dynasty', 'content', 'source_type', 'display_status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(PoemTag::class, PoemTagAssign::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PoemImage::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(UserPoemHistoryRecord::class);
    }

    protected function casts(): array
    {
        return [
            'source_type' => PoemSourceTypes::class,
            'display_status' => DisplayStatuses::class,
        ];
    }
}
