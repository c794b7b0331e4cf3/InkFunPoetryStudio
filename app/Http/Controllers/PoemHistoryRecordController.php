<?php

namespace App\Http\Controllers;

use App\Http\Services\InertiaMessageService;
use App\Models\UserPoemHistoryRecord;
use Illuminate\Support\Facades\Auth;

class PoemHistoryRecordController
{
    public function delete(UserPoemHistoryRecord $item)
    {
        $userId = Auth::id();

        if ($item->user_id != $userId) {
            return response(status: 403);
        }

        $item->delete();

        InertiaMessageService::success('删除成功');

        return response(status: 204);
    }

    public function clearAll()
    {
        $userId = Auth::id();

        UserPoemHistoryRecord::query()
            ->where([
                'user_id' => $userId,
            ])->delete();

        InertiaMessageService::success('删除成功');

        return response(status: 204);
    }
}
