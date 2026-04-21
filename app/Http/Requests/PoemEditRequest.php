<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PoemEditRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => [
                'nullable',
                'string',
            ],
            'author' => [
                'nullable',
                'string',
            ],
            'dynasty' => [
                'nullable',
                'string',
            ],
            'content' => [
                'required',
                'string',
            ],
        ];
    }
}
