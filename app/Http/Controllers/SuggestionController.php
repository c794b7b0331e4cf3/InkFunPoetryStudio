<?php

namespace App\Http\Controllers;

use App\Http\Requests\SuggestionSubmitRequest;
use App\Models\Suggestion;

class SuggestionController
{
    public function submit(SuggestionSubmitRequest $request)
    {
        $data = $request->validated();

        Suggestion::query()
            ->create([
                'content' => $data['content'],
            ]);

        return response(status: 201);
    }
}
