<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;

class UserController
{
    public function render(User $item)
    {
        return Inertia::render('user', [
            'user' => $item,
        ]);
    }
}
