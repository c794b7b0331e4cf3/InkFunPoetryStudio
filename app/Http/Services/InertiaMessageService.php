<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Session;

class InertiaMessageService
{
    public static function push(string $type, string $content): void
    {
        Session::push('inertia.messages', [
            'type' => $type,
            'content' => $content,
        ]);
    }

    public static function success(string $content): void
    {
        static::push('success', $content);
    }

    public static function info(string $content): void
    {
        static::push('info', $content);
    }

    public static function error(string $content): void
    {
        static::push('error', $content);
    }

    public static function warning(string $content): void
    {
        static::push('info', $content);
    }
}
