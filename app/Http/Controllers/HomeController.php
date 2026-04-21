<?php

namespace App\Http\Controllers;

use App\Http\Resources\PoemImageResource;
use App\Models\PoemImage;
use Inertia\Inertia;

class HomeController
{
    public function render()
    {
        return Inertia::render('index', [
            'poem_images' => PoemImageResource::collection(
                PoemImage::query()
                    ->with(['poem', 'poem.tags', 'poem.user', 'file'])
                    ->withCount(['likes'])
                    ->inRandomOrder()
                    ->limit(10)
                    ->get()
            ),
        ]);
    }
}
