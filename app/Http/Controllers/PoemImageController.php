<?php

namespace App\Http\Controllers;

use App\Http\Services\InertiaMessageService;
use App\Models\PoemImage;
use Illuminate\Support\Facades\Auth;

class PoemImageController
{
    public function like(PoemImage $item)
    {
        $userId = Auth::id();

        $liked = $item->likes()
            ->where('user_id', $userId)
            ->exists();

        if ($liked) {
            $item->likes()
                ->where('user_id', $userId)
                ->delete();

            InertiaMessageService::success('取消收藏成功');

            return response(status: 204);
        }

        $item->likes()
            ->create([
                'user_id' => $userId,
            ]);

        InertiaMessageService::success('收藏成功');

        return response(status: 201);
    }

    public function delete(PoemImage $item)
    {
        $userId = Auth::id();

        if ($item->user_id != $userId) {
            return response(status: 403);
        }

        $item->delete();

        InertiaMessageService::success('删除成功');

        return response(status: 204);
    }
}
