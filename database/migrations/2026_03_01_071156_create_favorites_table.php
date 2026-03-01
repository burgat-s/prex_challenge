<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('gif_id'); 
            $table->string('alias');
            $table->string('provider')->default('giphy'); 
            $table->timestamps();

            $table->unique(['user_id', 'gif_id', 'provider']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};