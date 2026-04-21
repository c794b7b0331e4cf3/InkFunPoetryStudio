<?php

namespace App\Http\Services;

use Illuminate\Support\Carbon;

class GreetingService
{
    public static function generate(): string
    {
        $hour = Carbon::now()
            ->setTimezone(
                config('app.timezone')
            )->hour;

        if ($hour >= 5 && $hour < 12) {
            return '早上好';
        }

        if ($hour >= 12 && $hour < 18) {
            return '下午好';
        }

        if ($hour >= 18) {
            return '晚上好';
        }

        return '你好';
    }
}
