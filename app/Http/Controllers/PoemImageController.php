<?php

namespace App\Http\Controllers;

use App\Http\Requests\PoemImageCommentLoadRequest;
use App\Http\Requests\PoemImageCommentSendRequest;
use App\Http\Resources\PoemImageCommentResource;
use App\Http\Services\InertiaMessageService;
use App\Models\PoemImage;
use App\Models\PoemImageComment;
use Illuminate\Database\Eloquent\Builder;
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

    public function comments(PoemImage $item, PoemImageCommentLoadRequest $request)
    {
        $data = $request->validated();

        return PoemImageCommentResource::collection(
            PoemImageComment::query()
                ->where('poem_image_id', $item->id)
                ->when(isset($data['parent_id']), function (Builder $query) use ($data) {
                    $query->where('parent_id', $data['parent_id']);
                })
                ->get()
        );
    }

    public function sendComment(PoemImage $item, PoemImageCommentSendRequest $request)
    {
        $data = $request->validated();

        PoemImageComment::query()
            ->create([
                'poem_image_id' => $item->id,
                'parent_id' => $data['parent_id'] ?? null,
                'user_id' => Auth::id(),
                'content' => $data['content'],
            ]);

        return response(status: 201);
    }

    public function deleteComment(PoemImageComment $item)
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
