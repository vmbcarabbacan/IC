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
            $table->unsignedBigInteger('name')->comment('car variant');
            $table->unsignedBigInteger('car_make_id');
            $table->unsignedBigInteger('car_model_id');
            $table->unsignedBigInteger('car_year');
            $table->integer('cylinders')->default(4);
            $table->integer('no_seats')->default(4);
            $table->boolean('is_vintage')->default(false);
            $table->float('new_min', 12, 2);
            $table->float('new_max', 12, 2);
            $table->float('old_min', 12, 2);
            $table->float('old_max', 12, 2);
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
