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
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'ticket_type_id')) {
                $table->foreignId('ticket_type_id')->nullable()->after('service_plan_id')->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('orders', 'quantity')) {
                $table->integer('quantity')->default(1)->after('amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['ticket_type_id']);
            $table->dropColumn(['ticket_type_id', 'quantity']);
        });
    }
};
