<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('episodes', function (Blueprint $table) {
            $table->string('media_type')->default('image')->after('image');
            $table->string('media_source')->default('url')->after('media_type');
            $table->text('video_url')->nullable()->after('media_source');
        });
    }

    public function down(): void
    {
        Schema::table('episodes', function (Blueprint $table) {
            $table->dropColumn(['media_type', 'media_source', 'video_url']);
        });
    }
};
