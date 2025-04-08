<?php

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Reservation;
use App\Models\User;
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
            $table->foreignIdFor(Reservation::class)->constrained('reservations')->cascadeOnDelete()->cascadeOnUpdate();
            
            $table->decimal('sub_total')->default(0);
            $table->decimal('total_amount')->default(0);
            $table->decimal('balance')->default(0);

            $table->date('issue_date')->nullable();
            $table->date('due_date')->nullable();
            $table->smallInteger('status')->default(InvoiceStatus::PENDING);
            $table->string('note')->nullable();

            $table->decimal('waive_amount', 10, 2)->default(0);
            $table->text('waive_reason')->nullable();
            $table->foreignIdFor(User::class, 'waived_by')->nullable()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->softDeletes();
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
