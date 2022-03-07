<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlCredentials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sl_credentials', function (Blueprint $table) {
            $table->string('wk_leads_last_record_founded_datetime',100)->nullable()->after('cadence_last_record_founded_datetime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sl_credentials', function (Blueprint $table) {
            //
        });
    }
}
