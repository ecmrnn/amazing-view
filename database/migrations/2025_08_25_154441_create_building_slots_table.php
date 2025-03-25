<?php

use App\Models\Building;
use App\Models\BuildingSlot;
use App\Models\Room;
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
        Schema::create('building_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Building::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignIdFor(Room::class)->nullable()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('floor');
            $table->integer('row');
            $table->integer('col');
            $table->timestamps();
        });

        // Add building_slot_id to the 'rooms' table
        Schema::table('rooms', function (Blueprint $table) {
            $table->foreignIdFor(BuildingSlot::class)->after('building_id')->nullable()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('building_slots');

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('building_slot_id');
        });
    }
};
