<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;

class UserAuthController
{
    public function register(RegisterRequest $request)
    {
        return back();
    }
}
