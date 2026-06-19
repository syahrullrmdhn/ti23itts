<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_wall_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('author_nim', 20);
            $table->string('author_name');
            $table->text('message');
            $table->timestamps();

            $table->unique(['student_id', 'author_nim']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_wall_messages');
    }
};
