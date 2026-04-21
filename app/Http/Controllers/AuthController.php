<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Services\InertiaMessageService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController
{
    public function render()
    {
        return Inertia::render('auth');
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create($data);

        Auth::login($user);

        InertiaMessageService::success('注册成功 !');

        return to_route('profile.render');
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $user = User::query()
            ->where('name', $data['name'])
            ->first();

        if ($user === null) {
            InertiaMessageService::error('用户名或密码错误');

            return back();
        }

        if (! Hash::check($data['password'], $user->password)) {
            InertiaMessageService::error('用户名或密码错误');

            return back();
        }

        Auth::login($user);

        InertiaMessageService::success('登录成功 !');

        return to_route('profile.render');
    }

    public function logout()
    {
        Auth::logout();

        InertiaMessageService::success('登出成功 !');

        return to_route('auth.render');
    }
}
