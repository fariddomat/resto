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
        Schema::create('daily_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_item_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('total_price', 8, 2);
            $table->boolean('is_taxable')->default(false);
            $table->decimal('tax_rate', 5, 2)->nullable();
            $table->decimal('total_tax', 8, 2)->nullable();
            $table->text('note')->nullable();
            $table->string('status')->default('pending');
            $table->date('purchase_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_purchases');
    }
};
