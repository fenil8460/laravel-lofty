<?php

namespace App\Models\salesloft;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slusers extends Model
{
    use HasFactory;
    protected $table = 'sl_users';

    protected $fillable = [
        'salesloft_user_id',
        'guid',
        'salesloft_created_at',
        'salesloft_updated_at',
        'name',
        'first_name',
        'last_name',
        'job_role',
        'active',
        'time_zone',
        'slack_username',
        'twitter_handle',
        'email',
        'email_client_email_address',
        'sending_email_address',
        'from_address',
        'full_email_address',
        'bcc_email_address',
        'email_signature',
        'email_signature_type',
        'email_signature_click_tracking_disabled',
        'team_admin',
        'local_dial_enabled',
        'click_to_call_enabled',
        'email_client_configured',
        'crm_connected',
        'phone_client_id',
        'phone_number_assignment_id',
        'phone_number_assignment_href',
        'group_id',
        'group_href',
        'team_id',
        'team_href',
        'role_id',
        'is_delete',
        'salesLoft_account_id',
    ];

    public function getslUsers()
    {
        $data = Slusers::select('salesloft_user_id', 'name')
            ->groupBy('salesloft_user_id')
            ->get();
        return $data;
    }

    public function getGroupUser($id){
        $data = Slusers::selectRaw('group_concat(name) as reps_name')
        ->whereIn('salesloft_user_id',$id)
        ->get();
    return $data;
    }
}
