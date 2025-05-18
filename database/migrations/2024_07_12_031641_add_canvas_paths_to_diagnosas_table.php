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
        Schema::table('diagnosas', function (Blueprint $table) {
            $table->string('canvas_output_path')->nullable();
            $table->string('mask_canvas_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diagnosas', function (Blueprint $table) {
            $table->dropColumn(['canvas_output_path', 'mask_canvas_path']);
        });
    }
};
