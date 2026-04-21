<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PoemTag extends Model
{
    protected $fillable = ['name'];

    public function assigns(): HasMany
    {
        return $this->hasMany(PoemTagAssign::class, 'poem_tag_id');
    }
}
