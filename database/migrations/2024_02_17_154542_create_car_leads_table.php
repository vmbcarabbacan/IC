<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('car_leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->comment('Customer id');
            $table->unsignedBigInteger('customer_detail_id')->comment('Customer details');
            $table->unsignedBigInteger('car_driver_detail_id')->comment('Driver details');
            $table->integer('status')->default(0);
            $table->integer('status_old')->default(0);
            $table->integer('disposition_id')->default(0);
            $table->string('session_id')->default(Str::random(15));
            $table->string('btm_source')->default('application');
            $table->string('utm_source')->default('organic');
            $table->string('utm_campaign')->default('organic');
            $table->string('utm_content')->default('organic');
            $table->string('utm_medium')->default('organic');
            $table->string('utm_term')->default('organic');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_leads');
    }
};

