<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('history_payments', function (Blueprint $table) {
            // Track if this payment is reversed
            $table->boolean('is_reversed')->default(false)->after('type_of_money');
            
            // Track which reversal payment this is linked to (for audit trail)
            $table->unsignedBigInteger('reversed_by_id')->nullable()->after('is_reversed');
            $table->foreign('reversed_by_id')
                ->references('id')
                ->on('history_payments')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('history_payments', function (Blueprint $table) {
            $table->dropForeign(['reversed_by_id']);
            $table->dropColumn(['is_reversed', 'reversed_by_id']);
        });
    }
};
