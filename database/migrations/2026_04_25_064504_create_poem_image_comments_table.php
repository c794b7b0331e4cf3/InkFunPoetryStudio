<?php

use App\Models\PoemImage;
use App\Models\PoemImageComment;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poem_image_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PoemImageComment::class, 'parent_id')->nullable();
            $table->foreignIdFor(PoemImage::class);
            $table->foreignIdFor(User::class);
            $table->string('content');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poem_image_comments');
    }
};
