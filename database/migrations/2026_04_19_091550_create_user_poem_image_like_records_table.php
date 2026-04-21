<?php

use App\Models\PoemImage;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_poem_image_like_records', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(PoemImage::class);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_poem_image_like_records');
    }
};
