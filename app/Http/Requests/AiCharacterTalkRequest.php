<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class AiCharacterTalkRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'character' => [
                'nullable',
                'string',
                Rule::in(
                    Arr::map(config('services.bailian.characters'), function (array $data) {
                        return $data['name'];
                    })
                ),
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
                'required',
                'string',
            ],
        ];
    }
}
