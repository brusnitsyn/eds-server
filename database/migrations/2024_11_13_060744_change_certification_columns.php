<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Синхронизация с МИС.
     */
    public function up(): void
    {
        Schema::table('certifications', function (Blueprint $table) {
            $table->string('mis_serial_number')->nullable();
            $table->string('mis_valid_from')->nullable();
            $table->string('mis_valid_to')->nullable();
            $table->boolean('mis_is_identical')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('certifications', function (Blueprint $table) {
            $table->dropColumn(['mis_serial_number', 'mis_valid_from', 'mis_valid_to', 'mis_is_identical']);
        });
    }
};
