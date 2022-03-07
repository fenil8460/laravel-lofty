<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCadenceColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sl_cadence', function (Blueprint $table) {
            $table->integer('salesLoft_account_id')->nullable()->after('counts_meetings_booked');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sl_cadence', function (Blueprint $table) {
            $table->dropColumn('salesLoft_account_id');
        });
    }
}
