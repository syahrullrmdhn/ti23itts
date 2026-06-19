<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('episode_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('episode_id')->constrained()->cascadeOnDelete();
            $table->string('student_nim', 20);
            $table->string('student_name');
            $table->timestamps();

            $table->unique(['episode_id', 'student_nim']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('episode_likes');
    }
};
