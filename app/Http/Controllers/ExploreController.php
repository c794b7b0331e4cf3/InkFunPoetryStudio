<?php

namespace App\Http\Controllers;

use App\Http\Resources\PoemImageResource;
use App\Http\Resources\PoemResource;
use App\Http\Resources\UserResource;
use App\Models\Poem;
use App\Models\PoemImage;
use App\Models\User;
use Inertia\Inertia;

class ExploreController
{
    public function render()
    {
        return Inertia::render('explore', [
            'poems' => Inertia::optional(function () {
                return PoemResource::collection(
                    Poem::query()
                        ->latest()
                        ->with(['tags', 'images', 'images.file'])
                        ->paginate(pageName: 'poems_page')
                );
            }),
            'poemImages' => Inertia::optional(function () {
                return PoemImageResource::collection(
                    PoemImage::query()
                        ->latest()
                        ->with(['poem', 'poem.tags', 'file'])
                        ->withCount(['likes'])
                        ->paginate(pageName: 'poem_images_page')
                );
            }),
            'leaderboard' => Inertia::optional(function () {
                return UserResource::collection(
                    User::query()
                        ->withCount(['poemImages'])
                        ->orderByDesc('poem_images_count')
                        ->paginate(pageName: 'leaderboard_page')
                );
            }),
        ]);
    }
}
