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
        Schema::create('seats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_section_id')->constrained('event_sections')->cascadeOnDelete();
            $table->string('seat_number'); // e.g. A1, A2, B5
            $table->string('row_label')->nullable(); // e.g. A, B, C
            $table->enum('status', ['available', 'reserved', 'booked'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
