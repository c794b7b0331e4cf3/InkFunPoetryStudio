<?php

namespace App\Http\Requests;

use App\Models\PoemImageComment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PoemImageCommentSendRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists(PoemImageComment::class, 'id'),
            ],
            'content' => [
                'required',
                'string',
            ],
        ];
    }
}
