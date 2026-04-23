<?php

namespace App\Http\Controllers;

use App\Http\Resources\PoemImageResource;
use App\Http\Resources\PoemResource;
use App\Http\Resources\UserResource;
use App\Models\Poem;
use App\Models\PoemImage;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
                        ->with([
                            'images' => function (HasMany $query) {
                                $query->withCount(['likes']);
                            },
                            'images.poem.user',
                            'images.poem',
                            'images.poem.tags',
                            'images.file',
                        ])
                        ->paginate(3, pageName: 'poems_page')
                );
            }),
            'poemImages' => Inertia::optional(function () {
                return PoemImageResource::collection(
                    PoemImage::query()
                        ->latest()
                        ->with(['poem', 'poem.user', 'poem.tags', 'file'])
                        ->withCount(['likes'])
                        ->paginate(pageName: 'poem_images_page')
                );
            }),
            'leaderboard' => Inertia::optional(function () {
                return UserResource::collection(
                    User::query()
                        ->has('poemImages')
                        ->withCount(['poemImages'])
                        ->orderByDesc('poem_images_count')
                        ->paginate(3, pageName: 'leaderboard_page')
                );
            }),
        ]);
    }
}
