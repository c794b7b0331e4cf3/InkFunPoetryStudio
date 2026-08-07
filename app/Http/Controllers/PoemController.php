<?php

namespace App\Http\Controllers;

use App\Http\Requests\PoemEditRequest;
use App\Http\Resources\PoemResource;
use App\Http\Services\InertiaMessageService;
use App\Models\Poem;
use App\Models\PoemImage;
use App\Models\UserPoemHistoryRecord;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PoemController
{
    public function render(Poem $item)
    {
        $userId = Auth::id();

        $item->setRelations([
            'images' => PoemImage::query()
                ->with([
                    'poem.user',
                    'poem',
                    'poem.tags',
                    'file',
                ])
                ->withCount([
                    'likes',
                    'comments',
                ])
                ->whereHas('poem', function (Builder $query) use ($item) {
                    $query->where('content', $item->content);
                })
                ->get(),
        ]);

        if ($userId !== null) {
            UserPoemHistoryRecord::query()
                ->where([
                    'user_id' => $userId,
                    'poem_id' => $item->id,
                ])->delete();

            UserPoemHistoryRecord::query()
                ->create([
                    'user_id' => $userId,
                    'poem_id' => $item->id,
                ]);
        }

        return Inertia::render('poem', [
            'poem' => new PoemResource($item),
        ]);
    }

    public function delete(Poem $item)
    {
        $userId = Auth::id();

        if ($item->user_id != $userId) {
            return response(status: 403);
        }

        $item->delete();

        InertiaMessageService::success('删除成功');

        return response(status: 204);
    }

    public function renderEdit(Poem $item)
    {
        $userId = Auth::id();

        if ($item->user_id != $userId) {
            InertiaMessageService::error('无权修改');

            return back();
        }

        return Inertia::render('edit_poem', [
            'poem' => new PoemResource($item),
        ]);
    }

    public function edit(Poem $item, PoemEditRequest $request)
    {
        $data = $request->validated();

        $userId = Auth::id();

        if ($item->user_id != $userId) {
            InertiaMessageService::error('无权修改');

            return back();
        }

        $item->update($data);

        InertiaMessageService::success('修改成功');

        return back();
    }
}
