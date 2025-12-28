<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'slug')) {
                $table->string('slug')->unique()->after('title');
            }
            if (!Schema::hasColumn('events', 'external_url')) {
                $table->string('external_url')->nullable()->after('registration_url');
            }
            if (!Schema::hasColumn('events', 'thumbnail')) {
                $table->string('thumbnail')->nullable()->after('featured_image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['slug', 'external_url', 'thumbnail']);
        });
    }
};
