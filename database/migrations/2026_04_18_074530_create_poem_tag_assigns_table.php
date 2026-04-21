<?php

use App\Models\Poem;
use App\Models\PoemTag;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poem_tag_assigns', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PoemTag::class);
            $table->foreignIdFor(Poem::class);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poem_tag_assigns');
    }
};
