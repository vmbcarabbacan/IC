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
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('User');
            $table->integer('role_id')->default(0);
            $table->unsignedBigInteger('team_leader_id')->default(0)->comment('Team leader');
            $table->unsignedBigInteger('underwriter_id')->default(0)->comment('Underwriter');
            $table->boolean('is_underwriter')->default(false);
            $table->boolean('is_round_robin')->default(false);
            $table->integer('agent_type')->default(0)->comment('1. New Leads, 2. Renewals, 3. Both');
            $table->integer('renewal_deals')->default(0)->comment('1. Self Deals, 2. All Deals, 3. Lost Leads');
            $table->integer('insurance_type')->default(0);
            $table->integer('failed_attempt')->default(0);
            $table->boolean('status')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
