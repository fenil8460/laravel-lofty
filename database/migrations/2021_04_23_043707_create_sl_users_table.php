<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sl_users', function (Blueprint $table) {
            $table->id('id');
            $table->integer('salesloft_user_id')->unique();
            $table->string('guid',100)->nullable();
            $table->string('salesloft_created_at',100)->nullable();
            $table->string('salesloft_updated_at',100)->nullable();
            $table->string('name',100)->nullable();
            $table->string('first_name',100)->nullable();
            $table->string('last_name',100)->nullable();
            $table->string('job_role',100)->nullable();
            $table->boolean('active')->nullable();
            $table->string('time_zone',100)->nullable();    
            $table->string('slack_username',100)->nullable();
            $table->string('twitter_handle',100)->nullable();
            $table->string('email',100)->nullable();
            $table->string('email_client_email_address',100)->nullable();
            $table->string('sending_email_address',100)->nullable();
            $table->string('from_address',100)->nullable();
            $table->string('full_email_address',100)->nullable();
            $table->string('bcc_email_address',100)->nullable();
            $table->text('email_signature')->nullable();
            $table->string('email_signature_type',100)->nullable();
            $table->boolean('email_signature_click_tracking_disabled')->nullable();
            $table->boolean('team_admin')->nullable();
            $table->boolean('local_dial_enabled')->nullable();
            $table->boolean('click_to_call_enabled')->nullable();
            $table->boolean('email_client_configured')->nullable();
            $table->boolean('crm_connected')->nullable();
            $table->integer('phone_client_id')->nullable();
            $table->integer('phone_number_assignment_id')->nullable();
            $table->string('phone_number_assignment_href',100)->nullable();
            $table->integer('group_id')->nullable();
            $table->string('group_href',100)->nullable();
            $table->integer('team_id')->nullable();
            $table->string('team_href',100)->nullable();
            $table->string('role_id',100)->nullable();
            $table->boolean('is_delete')->default(false);
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
        Schema::dropIfExists('sl_users');
    }
}
