<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlCadenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sl_cadence', function (Blueprint $table) {
            $table->id('id');
            $table->integer('sl_cadence_id')->nullable();
            $table->string('salesloft_created_at',100)->nullable();
            $table->string('salesloft_updated_at',100)->nullable();
            $table->string('archived_at',100)->nullable();
            $table->boolean('team_cadence')->nullable();
            $table->boolean('shared')->nullable();
            $table->boolean('remove_bounces_enabled')->nullable();
            $table->boolean('remove_replies_enabled')->nullable();
            $table->boolean('opt_out_link_included')->nullable();
            $table->boolean('draft')->nullable();
            $table->integer('cadence_framework_id')->nullable();
            $table->string('cadence_function',100)->nullable();
            $table->string('name',100)->nullable();
            $table->json('tags')->nullable();
            $table->integer('creator_id')->nullable();
            $table->string('creator_href',100)->nullable();
            $table->integer('owner_id')->nullable();
            $table->string('owner_href',100)->nullable();
            $table->integer('bounced_stage_id')->nullable();
            $table->string('bounced_stage_href',100)->nullable();
            $table->integer('replied_stage_id')->nullable();
            $table->string('replied_stage_href',100)->nullable();
            $table->integer('added_stage_id')->nullable();
            $table->string('added_stage_href',100)->nullable();
            $table->integer('finished_stage_id')->nullable();
            $table->string('finished_stage_href',100)->nullable();
            $table->integer('cadence_priority_id')->nullable();
            $table->string('cadence_priority_href',100)->nullable();
            $table->integer('counts_cadence_people')->nullable();
            $table->integer('counts_people_acted_on_count')->nullable();
            $table->integer('counts_target_daily_people')->nullable();
            $table->integer('counts_opportunities_created')->nullable();
            $table->integer('counts_meetings_booked')->nullable();
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
        Schema::dropIfExists('sl_cadence');
    }
}
