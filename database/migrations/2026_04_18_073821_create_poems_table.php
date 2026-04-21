<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('poems', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable();
            $table->string('title')->nullable();
            $table->string('author')->nullable();
            $table->string('dynasty')->nullable();
            $table->text('content');
            $table->unsignedTinyInteger('source_type');
            $table->unsignedTinyInteger('display_status');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poems');
    }
};
