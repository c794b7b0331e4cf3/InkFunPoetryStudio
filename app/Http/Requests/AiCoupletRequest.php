<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AiCoupletRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'history' => [
                'nullable',
                'array',
            ],
            'history.*' => [
                'nullable',
                'string',
            ],
            'input' => [
                'required',
                'string',
            ],
        ];
    }
}
