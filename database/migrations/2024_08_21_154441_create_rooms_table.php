<?php

use App\Models\Building;
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
            $table->foreignIdFor(RoomType::class);
            $table->foreignIdFor(Building::class);
            $table->string('room_number');
            $table->smallInteger('floor_number');
            $table->smallInteger('capacity');
            $table->string('image_1_path');
            $table->string('image_2_path');
            $table->string('image_3_path');
            $table->string('image_4_path');
            $table->smallInteger('status');
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
