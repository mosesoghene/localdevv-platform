<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'location')) {
                $table->string('location')->nullable()->after('event_time');
            }
            if (!Schema::hasColumn('events', 'max_attendees')) {
                $table->integer('max_attendees')->nullable()->after('event_type');
            }
            if (!Schema::hasColumn('events', 'registration_url')) {
                $table->string('registration_url')->nullable()->after('max_attendees');
            }
            if (!Schema::hasColumn('events', 'featured_image')) {
                $table->string('featured_image')->nullable()->after('is_published');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['location', 'max_attendees', 'registration_url', 'featured_image']);
        });
    }
};
