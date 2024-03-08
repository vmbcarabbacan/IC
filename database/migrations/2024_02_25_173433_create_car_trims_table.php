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
        Schema::create('car_trims', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Car Variant | Trim name');
            $table->unsignedBigInteger('car_make_id');
            $table->unsignedBigInteger('car_model_id');
            $table->unsignedBigInteger('car_year');
            $table->string('model_body')->nullable();
            $table->string('model_engine_position')->nullable();
            $table->string('model_engine_cc')->nullable();
            $table->string('model_engine_cyl')->nullable();
            $table->string('model_engine_type')->nullable();
            $table->string('model_engine_valves_per_cyl')->nullable();
            $table->string('model_engine_power_ps')->nullable();
            $table->string('model_engine_power_rpm')->nullable();
            $table->string('model_engine_torque_nm')->nullable();
            $table->string('model_engine_torque_rpm')->nullable();
            $table->string('model_engine_bore_mm')->nullable();
            $table->string('model_engine_stroke_mm')->nullable();
            $table->string('model_engine_compression')->nullable();
            $table->string('model_engine_fuel')->nullable();
            $table->string('model_top_speed_kph')->nullable();
            $table->string('model_0_to_100_kph')->nullable();
            $table->string('model_drive')->nullable();
            $table->string('model_transmission_type')->nullable();
            $table->string('model_seats')->nullable();
            $table->string('model_doors')->nullable();
            $table->string('model_weight_kg')->nullable();
            $table->string('model_length_mm')->nullable();
            $table->string('model_width_mm')->nullable();
            $table->string('model_height_mm')->nullable();
            $table->string('model_wheelbase_mm')->nullable();
            $table->string('model_lkm_hwy')->nullable();
            $table->string('model_lkm_mixed')->nullable();
            $table->string('model_lkm_city')->nullable();
            $table->string('model_fuel_cap_l')->nullable();
            $table->string('model_co2')->nullable();
            $table->string('make_country')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_trims');
    }
};
