<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('nim', 20)->nullable()->unique()->after('id');
            $table->string('email')->nullable()->after('name');
            $table->string('gender', 20)->nullable()->after('email');
            $table->string('birth_place')->nullable()->after('gender');
            $table->date('birth_date')->nullable()->after('birth_place');
            $table->string('religion', 50)->nullable()->after('birth_date');
            $table->string('phone', 30)->nullable()->after('religion');
            $table->string('class_type', 30)->nullable()->after('phone');
            $table->string('entry_type', 30)->nullable()->after('class_type');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropUnique(['nim']);
            $table->dropColumn([
                'nim',
                'email',
                'gender',
                'birth_place',
                'birth_date',
                'religion',
                'phone',
                'class_type',
                'entry_type',
            ]);
        });
    }
};
