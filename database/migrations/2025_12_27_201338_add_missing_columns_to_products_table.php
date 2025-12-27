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
        Schema::table('products', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('products', 'demo_url')) {
                $table->string('demo_url')->nullable()->after('version');
            }
            if (!Schema::hasColumn('products', 'documentation_url')) {
                $table->string('documentation_url')->nullable()->after('demo_url');
            }
            if (!Schema::hasColumn('products', 'features')) {
                $table->json('features')->nullable()->after('documentation_url');
            }
            if (!Schema::hasColumn('products', 'requirements')) {
                $table->json('requirements')->nullable()->after('features');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['demo_url', 'documentation_url', 'features', 'requirements']);
        });
    }
};
