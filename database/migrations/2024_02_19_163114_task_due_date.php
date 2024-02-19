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
        Schema::table('customer_details', function (Blueprint $table) {
            $table->date('task_due_date')->nullable()->after('session_id');
            $table->integer('task_count')->nullable()->after('task_due_date');
            $table->boolean('is_renewal')->default(false)->after('task_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_details', function (Blueprint $table) {
            $table->dropColumn('task_due_date');
            $table->dropColumn('task_count');
            $table->dropColumn('is_renewal');
        });
    }
};
