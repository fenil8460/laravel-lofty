<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlCallInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sl_call_informations', function (Blueprint $table) {
            $table->id('id');
            $table->integer('call_id')->nullable();
            $table->string('to',100)->nullable();
            $table->integer('duration')->nullable();
            $table->string('sentiment',100)->nullable();
            $table->string('disposition',100)->nullable();
            $table->string('salesloft_created_at')->nullable();
            $table->string('salesloft_updated_at')->nullable();
            $table->json('recordings')->nullable();
            $table->string('user_href',1024)->nullable();
            $table->integer('salesloft_user_id')->nullable();
            $table->integer('action_id')->nullable();
            $table->string('called_person_href',1024)->nullable();
            $table->integer('called_person_id')->nullable();
            $table->string('crm_activity_href',1024)->nullable();
            $table->integer('crm_activity_id')->nullable();
            $table->string('note_href',1024)->nullable();
            $table->string('cadence_href',1024)->nullable();
            $table->integer('cadence_id')->nullable();
            $table->string('step_href',1024)->nullable();
            $table->integer('step_id')->nullable();
            $table->integer('parent_id')->default('0');
            $table->string('direction',100)->nullable();
            $table->string('status',100)->nullable();
            $table->string('call_type',100)->nullable();
            $table->string('call_uuid',200)->nullable();
            $table->string('call_href',1024)->nullable();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sl_call_informations');
    }
}
