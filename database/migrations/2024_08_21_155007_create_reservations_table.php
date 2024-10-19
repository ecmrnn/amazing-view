<?php

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
            $table->date('resched_date_in')->nullable();
            $table->date('resched_date_out')->nullable();
            $table->date('cancel_date')->nullable();
            $table->integer('adult_count');
            $table->integer('children_count');
            $table->smallInteger('status');
            $table->string('proof_image_path')->nullable();
            $table->text('note')->nullable();

            // Optional parameter for reservation records
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();

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
