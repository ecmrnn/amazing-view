<?php

use App\Enums\RoomStatus;
use App\Models\Building;
use App\Models\BuildingSlot;
use App\Models\Room;
use App\Models\RoomType;
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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(RoomType::class)->constrained();
            $table->foreignIdFor(Building::class)->nullable()->constrained();
            $table->string('room_number');
            $table->smallInteger('floor_number')->nullable();
            $table->smallInteger('min_capacity');
            $table->smallInteger('max_capacity');
            $table->decimal('rate');
            $table->string('image_1_path')->nullable();
            $table->smallInteger('status')->default(RoomStatus::AVAILABLE);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
