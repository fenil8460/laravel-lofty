<?php

namespace App\Console\Commands;

use App\Models\salesloft\Slcallinfo;
use Illuminate\Console\Command;
use App\Models\salesloft\Slworkingleads;
use App\Models\salesloft\Slcredential;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;

class SalesloftPeople extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:getSalesloftPeople';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command use to insert  salesloft people record in database so we can find cadence report working leads';

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
        //get api data and store that data in database
        $credential = Slcredential::all();
        foreach ($credential as $credentials) {
            try {
                $cadence_people_count = '';
                $total_cadence_count = 0;
                //start date(log)
                $date = Carbon::now();
                Log::channel('slpeople')->notice('
            ------------------------------------------------------------
            Start Date = ' . $date->format('Y-m-d H:i:s.uP') . '
            Instance Name = ' . $credentials->instance_name . '
            ');
                $call_information = new Slcallinfo();
                $defaul_date = $call_information->getdate();
                $f_date =  $defaul_date[0]['minDate'];
                $l_date = $defaul_date[0]['maxDate'];
                $dails = $call_information->getSlCadenceName($f_date, $l_date);
                foreach ($dails as  $value) {
                    if ($credentials->id == $value->salesLoft_account_id) {
                        $getpeople =  getSalesLoftPeople1($value->cadence_id, $credentials->api_key); //helper function
                        $people = json_decode($getpeople);
                        $cadence_people_count .= 'cadence_id:' . $value->cadence_id . '=>peolpe_count:' . $people->metadata->paging->total_count . ', ';
                        $total_cadence_count = $total_cadence_count + 1;
                        $data = [
                            'salesLoft_account_id' => $value->salesLoft_account_id,
                            'date' => $date->format('Y-m-d'),
                            'sl_cadence_id' => isset($value->cadence_id) ? $value->cadence_id : null,
                            'lead_counts' => isset($people->metadata->paging->total_count) ? $people->metadata->paging->total_count : null,
                        ];
                        Slworkingleads::insert($data);
                    }
                }

                // End date(log)
                $endDate = Carbon::now();
                Log::channel('slpeople')->notice('
            Date = ' . Carbon::now() . '
            Cadence_Count = ' . $total_cadence_count . '
            Cadence_ID with people Count = ' . $cadence_people_count . '
            End Date = ' . $endDate->format('Y-m-d H:i:s.uP') . '
            ------------------------------------------------------------
            ');
            } catch (Throwable $e) {
                Log::channel('slpeople')->notice('

                Token is Invallid 
                
        End Date = ' . $endDate->format('Y-m-d H:i:s.uP') . '
        ------------------------------------------------------------
        ');
                report($e);
            }
        }
        return 0;
    }

    // public function handle()
    // {
    //     //get api data and store that data in database
    //     $credential = Slcredential::all();
    //     foreach ($credential as $credentials) {
    //         //start date(log)
    //         $date = Carbon::now();
    //         Log::channel('slpeople')->notice('
    //         ------------------------------------------------------------
    //         Start Date = ' . $date->format('Y-m-d H:i:s.uP') . '
    //         Instance Name = ' . $credentials->instance_name . '
    //         ');

    //         $total_data = 0;
    //         $store_log_id = '';
    //         if ($credentials->wk_leads_last_record_founded_datetime == null) {
    //             // $update_date = Carbon::now()->format('Y-m-d H:i:s.uP');
    //             $created_date =  Carbon::parse('1970-01-01 00:00:00')->format('Y-m-d H:i:s.uP');
    //         } else {
    //             $created_date =  $credentials->wk_leads_last_record_founded_datetime;
    //         }
    //         $page = 1;
    //         $last_date = '';
    //         do {
    //             $getpeople =  getSalesLoftPeople1($created_date, $page++, $credentials->api_key); //helper function
    //             $people = json_decode($getpeople);
    //             $total_data = $total_data + count($people->data);
    //             $total_page = $people->metadata->paging->total_pages;
    //             $log_id = '';
    //             foreach ($people->data as $datas) {
    //                 $last_date = isset($datas->created_at) ? $datas->created_at : null;
    //                 $log_id .= $datas->id . ',';
    //                 $custom_fields = json_encode($datas->custom_fields);
    //                 $tags = json_encode($datas->tags);
    //                 $contact_restrictions = json_encode($datas->contact_restrictions);
    //                 $counts = json_encode($datas->counts);
    //                 $data = [
    //                     'people_id'=> isset($datas->id) ? $datas->id : null,
    //                     'salesloft_created_at'=> isset($datas->created_at) ? $datas->created_at : null,
    //                     'salesloft_updated_at'=> isset($datas->updated_at) ? $datas->updated_at : null,
    //                     'most_recent_cadence_id'=> isset($datas->most_recent_cadence->id) ? $datas->most_recent_cadence->id : null,
    //                     'salesLoft_account_id'=> $credentials->id,
    //                 ];
    //                 //insert salesloft people record
    //                 Slworkingleads::insert($data);
    //                 if($last_date != null && $last_date != ''){
    //                     Slcredential::where('id', $credentials->id)->update([
    //                         'wk_leads_last_record_founded_datetime' => $last_date
    //                     ]);
    //                 }
    //             }

    //             $store_log_id .= $log_id;
    //         } while ($page <= $total_page);

    //         //End date(log)
    //         $endDate = Carbon::now();
    //         Log::channel('slpeople')->notice('
    //     Total Pull Call = ' . $total_data . '
    //     people id = ' . $store_log_id . '
    //     End Date = ' . $endDate->format('Y-m-d H:i:s.uP') . '
    //     ------------------------------------------------------------
    //     ');
    //     }
    //     return 0;
    // }
}
