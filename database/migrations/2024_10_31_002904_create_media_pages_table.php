<?php

use App\Models\Media;
use App\Models\Page;
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
        Schema::create('media_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Page::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Media::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_pages');
    }
};
