<?php

namespace App\Models;

use App\Enums\FileTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    protected $fillable = ['user_id', 'disk', 'path', 'original_filename', 'mimetype', 'size', 'ip', 'type', 'metadata', 'hash'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'type' => FileTypes::class,
            'metadata' => 'array',
        ];
    }
}
