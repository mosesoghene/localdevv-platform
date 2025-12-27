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
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name'); // e.g., "VIP", "Regular", "Early Bird"
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('total_quantity'); // Total tickets available
            $table->integer('sold_quantity')->default(0); // Tickets sold
            $table->integer('max_per_order')->default(10); // Max tickets per purchase
            $table->boolean('is_active')->default(true);
            $table->timestamp('sale_starts_at')->nullable();
            $table->timestamp('sale_ends_at')->nullable();
            $table->json('additional_info')->nullable(); // Perks, benefits, etc.
            $table->timestamps();
            
            $table->index(['event_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_types');
    }
};
