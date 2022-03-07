<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\salesloft\Slusers;
use App\Models\salesloft\Slcredential;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;

class SalesloftUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:getSalesloftUser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command use to insert  salesloft User record in database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //start date(log)
        $credential = Slcredential::all();
        foreach ($credential as $credentials) {
            try {
                $date = Carbon::now();
                Log::channel('sluser')->notice('
        ------------------------------------------------------------
        Start Date = ' . $date->format('Y-m-d H:i:s.uP') . '
        Instance Name = ' . $credentials->instance_name . '
        ');
                $total_data = 0;
                $store_log_id = '';
                $Update_log_id = '';
                //get api data and store that data in database
                $page = 1;
                do {
                    $user =  getSalesLoftUsers($page++, $credentials->api_key); //helper function
                    $data = json_decode($user);
                    $total_data = $total_data + count($data->data);
                    $total_page = $data->metadata->paging->total_pages;
                    $log_id = '';
                    $ulog_id = '';
                    foreach ($data->data as $datas) {

                        $data = [
                            'salesloft_user_id' => isset($datas->id) ? $datas->id : null,
                            'guid' => isset($datas->guid) ? $datas->guid : null,
                            'salesloft_created_at' => isset($datas->created_at) ? $datas->created_at : null,
                            'salesloft_updated_at' => isset($datas->updated_at) ? $datas->updated_at : null,
                            'name' => isset($datas->name) ? $datas->name : null,
                            'first_name' => isset($datas->first_name) ? $datas->first_name : null,
                            'last_name' => isset($datas->last_name) ? $datas->last_name : null,
                            'job_role' => isset($datas->job_role) ? $datas->job_role : null,
                            'active' => isset($datas->active) ? $datas->active : null,
                            'time_zone' => isset($datas->time_zone) ? $datas->time_zone : null,
                            'slack_username' => isset($datas->slack_username) ? $datas->slack_username : null,
                            'twitter_handle' => isset($datas->twitter_handle) ? $datas->twitter_handle : null,
                            'email' => isset($datas->email) ? $datas->email : null,
                            'email_client_email_address' => isset($datas->email_client_email_address) ? $datas->email_client_email_address : null,
                            'sending_email_address' => isset($datas->sending_email_address) ? $datas->sending_email_address : null,
                            'from_address' => isset($datas->from_address) ? $datas->from_address : null,
                            'full_email_address' => isset($datas->full_email_address) ? $datas->full_email_address : null,
                            'bcc_email_address' => isset($datas->bcc_email_address) ? $datas->bcc_email_address : null,
                            'email_signature' => isset($datas->email_signature) ? $datas->email_signature : null,
                            'email_signature_type' => isset($datas->email_signature_type) ? $datas->email_signature_type : null,
                            'email_signature_click_tracking_disabled' => isset($datas->email_signature_click_tracking_disabled) ? $datas->email_signature_click_tracking_disabled : null,
                            'team_admin' => isset($datas->team_admin) ? $datas->team_admin : null,
                            'local_dial_enabled' => isset($datas->local_dial_enabled) ? $datas->local_dial_enabled : null,
                            'click_to_call_enabled' => isset($datas->click_to_call_enabled) ? $datas->click_to_call_enabled : null,
                            'email_client_configured' => isset($datas->email_client_configured) ? $datas->email_client_configured : null,
                            'crm_connected' => isset($datas->crm_connected) ? $datas->crm_connected : null,
                            'phone_client_id' => isset($datas->phone_client->id) ? $datas->phone_client->id : null,
                            'phone_number_assignment_id' => isset($datas->phone_number_assignment->id) ? $datas->phone_number_assignment->id : null,
                            'phone_number_assignment_href' => isset($datas->phone_number_assignment->_href) ? $datas->phone_number_assignment->_href : null,
                            'group_id' => isset($datas->group->id) ? $datas->group->id : null,
                            'group_href' => isset($datas->group->_href) ? $datas->group->_href : null,
                            'team_id' => isset($datas->team->id) ? $datas->team->id : null,
                            'team_href' => isset($datas->team->_href) ? $datas->team->_href : null,
                            'role_id' => isset($datas->role->id) ? $datas->role->id : null,
                            'salesLoft_account_id' => $credentials->id,
                        ];

                        // Slusers::insert($data);         
                        //insert salesloft user record
                        // $get_all_user = Slusers::select('salesloft_user_id', 'salesloft_updated_at')
                        //     ->where('salesloft_user_id', $data['salesloft_user_id'])
                        //     ->where('salesloft_updated_at', '!=', $data['salesloft_updated_at'])
                        //     ->get();
                        // $get_latest_date = Slusers::select('salesloft_user_id', 'salesloft_created_at')
                        //     ->latest('salesloft_created_at')
                        //     ->first();
                        // dd($get_latest_date);
                        // $defualt_date = isset($get_latest_date->salesloft_created_at) ? $get_latest_date->salesloft_created_at : "1970-01-01";
                        // if (count($get_all_user) == 1) {
                        //     Slusers::where('salesloft_user_id', $data['salesloft_user_id'])
                        //         ->update($data);
                        //     $ulog_id .= $datas->id . ',';
                        //     echo "---<update>---";
                        // }
                        // if ($defualt_date < $data['salesloft_created_at']) {
                        //     Slusers::insert($data);
                        //     $log_id .= $datas->id . ',';
                        //     echo "---<insert>---";
                        // }
                        $user_count = Slusers::select('salesloft_user_id', 'salesloft_updated_at')
                            ->where('salesloft_user_id', $data['salesloft_user_id'])
                            ->count();
                        if ($user_count == 1) {
                            Slusers::where('salesloft_user_id', $data['salesloft_user_id'])
                                ->update($data);
                            $ulog_id .= $datas->id . ',';
                        } else {
                            Slusers::insert($data);
                            $log_id .= $datas->id . ',';
                        }
                    }
                    $store_log_id .= $log_id;
                    $Update_log_id .= $ulog_id;
                } while ($page <= $total_page);
                //End date(log)
                $endDate = Carbon::now();
                Log::channel('sluser')->notice('
        Total Pull User = ' . $total_data . '
        Insert User id = ' . $store_log_id . '
        Update User id = ' . $Update_log_id . '
        End Date = ' . $endDate->format('Y-m-d H:i:s.uP') . '
        ------------------------------------------------------------
        ');
            } catch (Throwable $e) {
                Log::channel('sluser')->notice('

                Token is Invallid 

        End Date = ' . $endDate->format('Y-m-d H:i:s.uP') . '
        ------------------------------------------------------------
        ');
                report($e);
            }
        }
        return 0;
    }
}
