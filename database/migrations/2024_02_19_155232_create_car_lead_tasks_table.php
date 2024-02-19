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
        Schema::create('car_lead_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->comment('Customer');
            $table->unsignedBigInteger('car_lead_id')->comment('Car Lead');
            $table->unsignedBigInteger('agent_id')->default(0)->comment('Agent');
            $table->dateTime('closed_at')->nullable();
            $table->date('due_date');
            $table->unsignedBigInteger('customer_status_id')->default(0)->comment('Customer Status');
            $table->unsignedBigInteger('lead_status_id')->default(0)->comment('Lead Status');
            $table->unsignedBigInteger('disposition_id')->default(0)->comment('Disposition Status');
            $table->unsignedBigInteger('lost_lead_reason_id')->default(0)->comment('Lost Lead Status');
            $table->string('task_notes')->nullable();
            $table->integer('status')->default(1);
            $table->boolean('is_renewal')->default(false);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_lead_tasks');
    }
};
