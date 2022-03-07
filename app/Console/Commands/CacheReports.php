<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Group;
use App\Models\GroupCadence;
use App\Models\GroupSlusers;
use App\Models\salesloft\Slcallinfo;
use App\Models\salesloft\Slworkingleads;
use App\Models\salesloft\Slcadence;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CacheReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:getCacheReports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command use to store report data in cache so reports load repidally';

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

        $group_name = "";
        $groupWise_cadence_id = "";
        $groupWise_reps_id = "";
        $date = Carbon::now();
        Log::channel('cacheReports')->notice('
    ------------------------------------------------------------
    Start Date = ' . $date->format('Y-m-d H:i:s.uP') . '');
        $groups = Group::all();
        foreach ($groups as $data) {
            $group_name .= $data->group_name . ',';
            $group_id = $data->id;
            $groupCadence = GroupCadence::selectRaw('group_concat(cadence_id) as cadence_ids,group_id')->whereIn('group_id', [$group_id])
                ->groupBy('group_id')->get();

            $groupSlusers = GroupSlusers::selectRaw('group_concat(sl_user_id) as slusers_ids,group_id')->whereIn('group_id', [$group_id])
                ->groupBy('group_id')->get();

            //get all data 
            $call_information = new Slcallinfo();
            $working_leads = new Slworkingleads();
            $cadence = new Slcadence();
            $defaul_date = $call_information->getdate();

            // store cadence report cacheing
            if (count($groupCadence) > 0 || $data->group_name == 'All') {
                $current_date = date('Y-m-d');
                $f_date = date('Y-m-01', strtotime($current_date));
                $l_date = date('Y-m-d', strtotime("-1 days"));
                $cadence_ids = array();
                $dails = $call_information->getSlCadenceName($f_date, $l_date);
                $cadence_ids = array();
                foreach ($dails as $key => $value) {
                    $cadence_ids[] = $value->cadence_id;
                }
                if ($data->group_name != 'All') {
                    //get cadence name, dispositon count, sentiment count
                    $cadence_ids = explode(',', $groupCadence[0]->cadence_ids);
                    $dails = $call_information->getSlCadence($cadence_ids, $f_date, $l_date);
                    $groupWise_cadence_id .= $data->group_name . "=>" . implode(',', $cadence_ids) . " || ";
                }

                //cadence name
                $cadence_name = $cadence->getSlCadenceName($cadence_ids);
                $c_name = array();
                foreach ($cadence_name as $val) {
                    $c_name[$val->sl_cadence_id] = $val->name;
                }

                //get working leads count
                $leads = $working_leads->getWorkingleads($cadence_ids, $f_date, $l_date);
                // $leads = $working_leads->getWorkingleads($cadence_ids);
                $w_leads = array();
                foreach ($leads as $val) {
                    $w_leads[$val->sl_cadence_id] = $val;
                }

                //get disposition count
                $disposition = $call_information->getSlDisposition($cadence_ids, $f_date, $l_date);
                $c_disposition = array();
                foreach ($disposition as $val) {
                    $c_disposition[$val->cadence_id][$val->disposition] = $val->disposition_count;
                }

                //get sentiment count
                $sentiment = $call_information->getSlSentiment($cadence_ids, $f_date, $l_date);
                $c_sentiment = array();
                foreach ($sentiment as $val) {
                    $c_sentiment[$val->cadence_id][$val->sentiment] = $val->sentiment_count;
                }
                if ($cadence_ids != null) {
                    //get dials count
                    $cadenceId_string = implode(",", $cadence_ids);;
                    $dails_count = $call_information->getSlDails($cadenceId_string, $f_date, $l_date);
                    $c_dails = array();
                    foreach ($dails_count as $val) {
                        $c_dails[$val->cadence_id] = $val->total;
                    }
                }


                foreach ($dails as $key => $value) {

                    //set cadence name
                    $dails[$key]->name = (isset($c_name[$value->cadence_id]) ? $c_name[$value->cadence_id] : null);

                    //set dails in set dails total count of calldata
                    $dails[$key]->dails = (isset($c_dails[$value->cadence_id]) ? $c_dails[$value->cadence_id] : 0);

                    //set working leads
                    $dails[$key]->leads = (isset($w_leads[$value->cadence_id]) ? $w_leads[$value->cadence_id]['wc_count'] : 0);

                    //set dails in disposition count
                    $dails[$key]->disposition = (isset($c_disposition[$value->cadence_id]) ? $c_disposition[$value->cadence_id] : 0);

                    ///set dails in sentiment count
                    $dails[$key]->sentiment = (isset($c_sentiment[$value->cadence_id]) ? $c_sentiment[$value->cadence_id] : 0);
                }
                Cache::put('cadence_' . $group_id, $dails, 5400);
            }
            $f_date =  $defaul_date[0]['minDate'];
            $l_date = $defaul_date[0]['maxDate'];

            //store reps and executive reports
            if (count($groupSlusers) > 0 || $data->group_name == 'All') {
                $flag = 1;
                if ($data->group_name != 'All') {
                    $rep_Ids = explode(',', $groupSlusers[0]->slusers_ids);
                    //get date,dails,day and talktime, disposition count, sentiment count
                    $team_total = $call_information->getSlExecutiveTeam($f_date, $l_date, $flag, $rep_Ids);
                    $executiv_disposition = $call_information->getSlExecutiveDisposition($f_date, $l_date, $flag, $rep_Ids);
                    $executiv_sentiment = $call_information->getSlExecutiveSentiment($f_date, $l_date, $flag, $rep_Ids);
                    $groupWise_reps_id .= $data->group_name . "=>" . implode(',', $rep_Ids) . " || ";
                } else {
                    //get date,dails,day and talktime, disposition count, sentiment count
                    $team_total = $call_information->getSlExecutiveTeam($f_date, $l_date, $flag);
                    $executiv_disposition = $call_information->getSlExecutiveDisposition($f_date, $l_date, $flag);
                    $executiv_sentiment = $call_information->getSlExecutiveSentiment($f_date, $l_date, $flag);
                }

                foreach ($team_total as $key => $value) {
                    $dis = array();
                    $set = array();
                    // mearge date,dails,day,talktime,disposition and sentiment data
                    foreach ($executiv_disposition as $dispositionRow) {
                        if ($value->team_total == $dispositionRow->team_total) {
                            $dis[$dispositionRow->disposition] = $dispositionRow->disposition_count;
                        }
                    }
                    //set team_total in dispositon array
                    $team_total[$key]->disposition = $dis;

                    foreach ($executiv_sentiment as $sentimentRow) {
                        if ($value->team_total == $sentimentRow->team_total) {
                            $set[$sentimentRow->sentiment] = $sentimentRow->sentiment_count;
                        }
                    }
                    //set team_total in sentiment array
                    $team_total[$key]->sentiment = $set;
                }

                Cache::put('executive_' . $group_id, $team_total, 5400);

                $current_date = date('Y-m-d');
                $f_date = date('Y-m-01', strtotime($current_date));
                $l_date = date('Y-m-d', strtotime("-1 days"));
                $repId = array();
                $rep_Ids = array();
                if ($data->group_name != 'All') {
                    $rep_Ids = explode(',', $groupSlusers[0]->slusers_ids);
                    $repId = $call_information->getSingleUserRepId($rep_Ids, $f_date, $l_date);
                } else {
                    $repId = $call_information->getSingleRepId($f_date, $l_date);
                    foreach ($repId as $key => $value) {
                        $rep_Ids[] = $value->salesloft_user_id;
                    }
                }

                //get dails, days, talktime
                $repname = $call_information->getSlSingleRepName($rep_Ids, $f_date, $l_date);
                $rep_details = array();
                foreach ($repname as $val) {
                    $rep_details[$val->salesloft_user_id] = [
                        'dails' => $val->dails,
                        'days' => $val->days,
                        'talktime' => $val->talktime,
                    ];
                }

                //get disposition count
                $disposition = $call_information->getSlSingleRepDisposition($rep_Ids, $f_date, $l_date);
                $disposition_details = array();
                foreach ($disposition as $val) {
                    $disposition_details[$val->salesloft_user_id][$val->disposition] = $val->disposition_count;
                }

                //get sentiment count
                $sentiment = $call_information->getSlSingleRepSentiment($rep_Ids, $f_date, $l_date);
                $sentiment_details = array();
                foreach ($sentiment as $val) {
                    $sentiment_details[$val->salesloft_user_id][$val->sentiment] = $val->sentiment_count;
                }

                foreach ($repId as $key => $value) {

                    //set reps name, dails, days, talktime
                    $repId[$key]->repname = isset($rep_details[$value->salesloft_user_id]) ? $rep_details[$value->salesloft_user_id] : 0;

                    //set disposition count
                    $repId[$key]->disposition = isset($disposition_details[$value->salesloft_user_id]) ? $disposition_details[$value->salesloft_user_id] : 0;

                    //set  sentiment count
                    $repId[$key]->sentiment = isset($sentiment_details[$value->salesloft_user_id]) ? $sentiment_details[$value->salesloft_user_id] : 0;
                }

                Cache::put('reps_' . $group_id, $repId, 5400);
            }
        }
        //End date(log)
        $endDate = Carbon::now();
        Log::channel('cacheReports')->notice('
        Group Name = ' . $group_name . '
        Group Cadence = ' . $groupWise_cadence_id . '
        Group Reps = ' . $groupWise_reps_id . '
        End Date = ' . $endDate->format('Y-m-d H:i:s.uP') . '
        ------------------------------------------------------------
        ');
        return 0;
    }
}
