<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPoemHistoryRecord extends Model
{
    protected $fillable = ['user_id', 'poem_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function poem(): BelongsTo
    {
        return $this->belongsTo(Poem::class);
    }
}
