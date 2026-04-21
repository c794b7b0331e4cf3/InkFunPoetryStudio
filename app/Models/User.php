<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $fillable = ['name', 'password'];

    protected $hidden = ['password', 'remember_token'];

    public function poemImages(): HasMany
    {
        return $this->hasMany(PoemImage::class, 'user_id');
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
