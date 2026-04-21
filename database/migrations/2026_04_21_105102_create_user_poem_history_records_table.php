<?php

use App\Models\Poem;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_poem_history_records', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Poem::class);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_poem_history_records');
    }
};
