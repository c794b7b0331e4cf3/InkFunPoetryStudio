<?php

namespace App\Http\Resources;

use App\Models\PoemImage;
use App\Models\UserPoemImageLikeRecord;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

/** @mixin PoemImage */
class PoemImageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'file' => new FileResource(
                $this->whenLoaded('file')
            ),
            'poem' => new PoemResource(
                $this->whenLoaded('poem')
            ),

            'liked' => value(function () {
                $userId = Auth::id();

                if ($userId === null) {
                    return false;
                }

                return UserPoemImageLikeRecord::query()
                    ->where([
                        'user_id' => $userId,
                        'poem_image_id' => $this->id,
                    ])
                    ->exists();
            }),
            'likes_count' => $this->whenCounted('likes'),
            'comments_count' => $this->whenCounted('comments'),
        ];
    }
}
