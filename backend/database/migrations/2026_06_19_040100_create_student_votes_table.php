<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('voter_nim', 20);
            $table->string('voter_name');
            $table->string('badge', 50);
            $table->timestamps();

            $table->unique(['student_id', 'voter_nim', 'badge']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_votes');
    }
};
