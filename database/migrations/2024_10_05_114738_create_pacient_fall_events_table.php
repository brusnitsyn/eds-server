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
        Schema::create('pacient_fall_events', function (Blueprint $table) {
            $table->id();
            $table->string('full_name')->nullable();
            $table->string('reason')->nullable();
            $table->string('place')->nullable();
            $table->string('held_event')->nullable();
            $table->text('consequence')->nullable();
            $table->date('date');
            $table->foreignIdFor(\App\Models\Division::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacient_fall_events');
    }
};
