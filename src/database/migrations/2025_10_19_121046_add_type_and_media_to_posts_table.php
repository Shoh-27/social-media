<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->enum('type', ['text', 'image', 'video', 'link'])->default('text')->after('content');
            $table->string('video')->nullable()->after('image');
            $table->string('link_url')->nullable()->after('video');
            $table->string('link_title')->nullable()->after('link_url');
            $table->text('link_description')->nullable()->after('link_title');
            $table->string('link_image')->nullable()->after('link_description');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['type', 'video', 'link_url', 'link_title', 'link_description', 'link_image']);
        });
    }
};
