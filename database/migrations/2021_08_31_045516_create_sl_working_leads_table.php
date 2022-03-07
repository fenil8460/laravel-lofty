<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlWorkingLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sl_working_leads', function (Blueprint $table) {
            $table->id('id');
            $table->integer('salesLoft_account_id')->nullable();
            $table->date('date')->nullable();
            $table->integer('sl_cadence_id')->nullable();
            $table->integer('lead_counts')->nullable();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            // $table->id('id');
            // $table->integer('people_id')->nullable();
            // $table->string('salesloft_created_at')->nullable();
            // $table->string('salesloft_updated_at')->nullable();
            // $table->integer('most_recent_cadence_id')->nullable();
            // $table->integer('salesLoft_account_id')->nullable();
            // $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            // $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sl_working_leads');
    }
}
