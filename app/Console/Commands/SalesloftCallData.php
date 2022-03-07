<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\salesloft\Slcallinfo;
use App\Models\salesloft\Slcredential;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;


class SalesloftCallData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:getSalesloftCallData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command use to insert  salesloft calldata record in database';

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
        $credential = Slcredential::all();
        foreach ($credential as $credentials) {
            try {
                //start date(log)
                $date = Carbon::now();
                Log::channel('slcalldata')->notice('
        ------------------------------------------------------------
        Start Date = ' . $date->format('Y-m-d H:i:s.uP') . '
        Instance Name = ' . $credentials->instance_name . '
        ');
                $total_data = 0;
                $store_log_id = '';
                $last_record_founded_datetime = "";
                //get api data and store that data in database

                $page = 1;
                if ($credentials->calldata_last_record_founded_datetime == null) {
                    $type = 'backword';
                    $update_date = Carbon::now()->format('Y-m-d H:i:s.uP');
                } else {
                    $type = 'forword';
                    $update_date =  $credentials->calldata_last_record_founded_datetime;
                }
                $get_first_record =  getSalesLoftCallData($update_date, 1, $type, $credentials->api_key); //helper function
                $get_date = json_decode($get_first_record);
                do {
                    if ($type == 'backword' && $page == 26) {
                        break;
                    }
                    $calldata =  getSalesLoftCallData($update_date, $page++, $type, $credentials->api_key); //helper function
                    $calldatas = json_decode($calldata);
                    $total_data = $total_data + count($calldatas->data);
                    $total_page = $calldatas->metadata->paging->total_pages;
                    $log_id = '';
                    foreach ($calldatas->data as $data) {
                        $last_record_founded_datetime = isset($data->updated_at) ? $data->updated_at : null;
                        $log_id .= isset($data->call->id) ? $data->call->id . ',' : null . ',';
                        $recordings = json_encode($data->recording);
                        $call_data = [
                            'call_id' => isset($data->call->id) ? $data->call->id : null,
                            'salesloft_created_at' => isset($data->created_at) ? $data->created_at : null,
                            'salesloft_updated_at' => isset($data->updated_at) ? $data->updated_at : null,
                            'to' => isset($data->to) ? $data->to : null,
                            'duration' => isset($data->duration) ? $data->duration : null,
                            'recordings' => isset($recordings) ? $recordings : null,
                            'user_href' => isset($data->user->_href) ? $data->user->_href : null,
                            'salesloft_user_id' => isset($data->user->id) ? $data->user->id : null,
                            'called_person_href' => isset($data->called_person->_href) ? $data->called_person->_href : null,
                            'called_person_id' => isset($data->called_person->id) ? $data->called_person->id : null,
                            'parent_id' => isset($data->call->id) ? $data->call->id : 0,
                            'direction' => isset($data->direction) ? $data->direction : null,
                            'status' => isset($data->status) ? $data->status : null,
                            'call_type' => isset($data->call_type) ? $data->call_type : null,
                            'call_uuid' => isset($data->call_uuid) ? $data->call_uuid : null,
                            'call_href' => isset($data->called_person->_href) ? $data->called_person->_href : null,
                            'salesLoft_account_id' => $credentials->id,
                        ];
                        //insert salesloft calldata record
                        Slcallinfo::insert($call_data);
                        if ($type == 'forword') {
                            Slcredential::where('id', $credentials->id)->update([
                                'calldata_last_record_founded_datetime' => $last_record_founded_datetime
                            ]);
                        }
                    }
                    $store_log_id .= $log_id;
                } while ($page <= $total_page);
                if ($credentials->cadence_last_record_founded_datetime == null) {
                    Slcredential::where('id', $credentials->id)->update([
                        'calldata_last_record_founded_datetime' => $get_date->data[0]->updated_at
                    ]);
                } elseif ($last_record_founded_datetime != null) {
                    Slcredential::where('id', $credentials->id)->update([
                        'calldata_last_record_founded_datetime' => $last_record_founded_datetime
                    ]);
                }
                //End date(log)
                $endDate = Carbon::now();
                Log::channel('slcalldata')->notice('
        Total Pull CallData = ' . $total_data . '
        Call id = ' . $store_log_id . '
        End Date = ' . $endDate->format('Y-m-d H:i:s.uP') . '
        ------------------------------------------------------------
        ');
            } catch (Throwable $e) {
                Log::channel('slcalldata')->notice('
    
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
