<?php

namespace App\Http\Resources;

use App\Models\PoemImageComment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin PoemImageComment */
class PoemImageCommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'user' => new UserResource($this->user),
            'children' => PoemImageCommentResource::collection($this->children),
        ];
    }
}
