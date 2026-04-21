<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AiPoeticChainRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'keywords' => [
                'nullable',
                'array',
            ],
            'keywords.*' => [
                'nullable',
                'string',
            ],
            'history' => [
                'nullable',
                'array',
            ],
            'history.*' => [
                'nullable',
                'string',
            ],
            'input' => [
                'nullable',
                'string',
            ],
        ];
    }
}
