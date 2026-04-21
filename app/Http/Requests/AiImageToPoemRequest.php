<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AiImageToPoemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'input' => [
                'required',
                'file',
                'image',
            ],
        ];
    }
}
