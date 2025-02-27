<?php

use App\Models\Invoice;
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
        Schema::create('invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Invoice::class)->constrained();
            $table->string('orid')->nullable();
            $table->string('transaction_id')->nullable();
            $table->decimal('amount')->nullable();
            $table->enum('payment_method', ['cash', 'gcash', 'bank transfer'])->nullable();
            $table->string('proof_image_path')->nullable();
            $table->enum('purpose', ['downpayment', 'security deposit', 'partial', 'full payment']);
            $table->date('payment_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_payments');
    }
};
