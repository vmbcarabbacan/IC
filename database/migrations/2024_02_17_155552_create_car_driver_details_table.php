<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('car_driver_details', function (Blueprint $table) {
            $table->id();
            $table->string('driver_name')->nullable();
            $table->string('dob')->nullable();
            $table->integer('nationality')->default(0);
            $table->integer('first_driving_country')->default(0);
            $table->string('car_first_registration_date')->nullable();
            $table->integer('driving_experience')->default(0);
            $table->string('expected_policy_start_date')->nullable();
            $table->boolean('is_personal_use')->default(false);
            $table->boolean('is_policy_active')->default(false);
            $table->boolean('is_fully_comprehensive')->default(false);
            $table->boolean('is_new')->default(false);
            $table->string('car_year')->nullable();
            $table->integer('brand_id')->default(0);
            $table->integer('model_id')->default(0);
            $table->integer('trim_level_id')->default(0);
            $table->unsignedFloat('car_value', 12, 2)->default(0);
            $table->integer('country')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_driver_details');
    }
};
