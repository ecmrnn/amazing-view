<?php

use App\Models\Reservation;
use App\Models\Room;
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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('rid')->nullable();
            $table->foreignIdFor(User::class)->nullable()->constrained();
            $table->date('date_in');
            $table->date('date_out');

            // Rescheduled Fields
            $table->unsignedBigInteger('rescheduled_from')->nullable();
            $table->foreign('rescheduled_from')->references('id')->on('reservations')->onDelete('set null');
            $table->unsignedBigInteger('rescheduled_to')->nullable();
            $table->foreign('rescheduled_to')->references('id')->on('reservations')->onDelete('set null');

            // Reservation Details
            $table->integer('senior_count')->nullable();
            $table->integer('pwd_count')->nullable();
            $table->integer('adult_count');
            $table->integer('children_count');
            $table->smallInteger('status');
            $table->text('note')->nullable();
            
            // Optional parameter for reservation records
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
