<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBadgeRecord extends Model
{
    protected $fillable = ['user_id', 'key', 'is_new'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'is_new' => 'boolean',
        ];
    }
}
