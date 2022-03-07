<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sl_credentials', function (Blueprint $table) {
            $table->id();
            $table->string('instance_name',100);
            $table->string('api_key');
            $table->boolean('active')->default(true);
            $table->string('call_last_record_founded_datetime',100)->nullable();
            $table->string('calldata_last_record_founded_datetime',100)->nullable();
            $table->string('cadence_last_record_founded_datetime',100)->nullable();
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sl_credentials');
    }
}
