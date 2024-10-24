<?php

use App\Models\Invoice;
use App\Models\Reservation;
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
            $table->string('iid')->nullable();
            $table->foreignIdFor(Reservation::class)->constrained();
            
            $table->decimal('total_amount')->default(0); /* added */
            $table->decimal('downpayment')->default(0); /* added */
            $table->decimal('balance')->default(0);

            $table->date('issue_date')->nullable();
            $table->date('due_date')->nullable();
            $table->smallInteger('status')->default(Invoice::STATUS_PARTIAL);
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
