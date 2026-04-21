<?php

namespace App\Http\Resources;

use App\Models\Poem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Poem */
class PoemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'dynasty' => $this->dynasty,
            'content' => $this->content,
            'source_type' => $this->source_type->serialize(),
            'display_status' => $this->display_status->serialize(),
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'tags' => PoemTagResource::collection(
                $this->whenLoaded('tags')
            ),
            'user' => new UserResource(
                $this->whenLoaded('user')
            ),
            'images' => PoemImageResource::collection(
                $this->whenLoaded('images')
            ),
        ];
    }
}
