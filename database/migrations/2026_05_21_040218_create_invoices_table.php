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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_invoice')->unique();
            $table->date('tanggal_invoice');
            $table->foreignId('delivery_order_id')->constrained('delivery_orders');
            $table->foreignId('customer_id')->constrained();
            $table->date('jatuh_tempo');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('total', 15, 2);
            $table->enum('status_invoice', ['draft', 'unpaid', 'partial', 'paid', 'cancelled'])->default('unpaid');
            $table->text('catatan')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
