<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\salesloft\Slcallinfo;
use Carbon\Carbon;
use App\Models\salesloft\Slworkingleads;
use App\Models\salesloft\Slcadence;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;



class SalesloftReport extends Controller
{
    //Cadence Report 
    public function slCadenceReport()
    {
        $get_key = Session::get('key');
        $group_data = getUserpermission($get_key);
        // from CacheReports Command
        $dails = Cache::get("cadence_" . $group_data['single_group']->id);
        if ($dails == null) {
            $dails = [];
        }

        return view('report.cadencereport', ['c_data' => $dails]);
    }

    //Cadence Report Filter
    function cadenceFilter(Request $request)
    {
        $get_key = Session::get('key');
        $group_data = getUserpermission($get_key);
        $campaigns = isset($group_data['single_group']->sl_cadence_ids) ? count($group_data['single_group']->sl_cadence_ids) : 0;
        $cadence_ids = isset($group_data['single_group']->sl_cadence_ids) ? $group_data['single_group']->sl_cadence_ids : [];
        $keys = $request->key;
        if ($campaigns > 0  || Auth::user()->user_role == 'Super Admin') {
            // perticular group and admin cache key
            if ((Auth::user()->user_role == 'Super Admin' && $group_data['single_group']->group_name == 'All') || $group_data['single_group']->group_name == 'All') {
                $cahce_key = $keys;
            } else {
                $cahce_key = $group_data['single_group']->group_name . '_cadence_' . $keys . '_' . Auth::user()->id;
            }


            if (Cache::get($cahce_key) == null || $cahce_key == null) {

                $call_information = new Slcallinfo();
                $all_dates = $call_information->getdate();
                if ($request->starting_date == "" && $request->ending_date == "") {
                    $f_date = $all_dates[0]['minDate'];
                    $l_date = $all_dates[0]['maxDate'];
                } else {
                    $f_date =  $request->starting_date;
                    $l_date =  $request->ending_date;
                }
                $working_leads = new Slworkingleads();
                $cadence = new Slcadence();
                //get cadence name, dispositon count, sentiment count
                $dails = $call_information->getSlCadenceName($f_date, $l_date);
                if ((Auth::user()->user_role == 'Super Admin' && $group_data['single_group']->group_name == 'All') || $group_data['single_group']->group_name == 'All') {
                    //get all leads count
                    $cadence_ids = array();
                    foreach ($dails as $key => $value) {
                        $cadence_ids[] = $value->cadence_id;
                    }
                } else {
                    $dails = $call_information->getSlCadence($cadence_ids, $f_date, $l_date);
                }

                //cadence name
                $cadence_name = $cadence->getSlCadenceName($cadence_ids);
                $c_name = array();
                foreach ($cadence_name as $val) {
                    $c_name[$val->sl_cadence_id] = $val->name;
                }

                //get working leads count
                $leads = $working_leads->getWorkingleads($cadence_ids, $f_date, $l_date);
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
                Cache::put($cahce_key, $dails, 60 * 60);
            } else {
                $dails = Cache::get($cahce_key);
            }

            return view('report.cadencefilter', ['c_data' => $dails])->render();
        }
    }

    // Executive Report
    function slExecutiveReport()
    {
        $get_key = Session::get('key');
        $group_data = getUserpermission($get_key);
        // from CacheReports Command
        $team_total = Cache::get('executive_' . $group_data['single_group']->id);
        if ($team_total == null) {
            $team_total = [];
        }
        $total_count = SalesloftReport::reportTotal($team_total);
        return view('report.executiv', ['e_data' => $team_total, 'executive_count' => $total_count]);
    }

    // Executive Report Filter
    function executiveFilter(Request $request)
    {
        $get_key = Session::get('key');
        $group_data = getUserpermission($get_key);
        $reps = isset($group_data['single_group']->sl_user_ids) ? count($group_data['single_group']->sl_user_ids) : 0;
        $rep_Ids = isset($group_data['single_group']->sl_user_ids) ? $group_data['single_group']->sl_user_ids : [];
        $key = $request->key;

        if ((Auth::user()->user_role == 'Super Admin' && $group_data['single_group']->group_name == 'All') || $group_data['single_group']->group_name == 'All') {
            $cahce_key = $key;
        } else {
            $cahce_key = $group_data['single_group']->group_name . '_executive_' . $key . '_' . Auth::user()->id;
        }

        if (Cache::get($cahce_key) == null || $key == null) {
            $call_information = new Slcallinfo();
            $f_date =  isset($request->starting_date) ? $request->starting_date : "";
            $l_date =  isset($request->ending_date) ? $request->ending_date : "";

            if ((Auth::user()->user_role == 'Super Admin' && $group_data['single_group']->group_name == 'All') || $group_data['single_group']->group_name == 'All') {
                //get date,dails,day and talktime, disposition count, sentiment count
                $team_total = $call_information->getSlExecutiveTeam($f_date, $l_date, '');
                $executiv_disposition = $call_information->getSlExecutiveDisposition($f_date, $l_date, '');
                $executiv_sentiment = $call_information->getSlExecutiveSentiment($f_date, $l_date, '');
            } else {
                //get date,dails,day and talktime, disposition count, sentiment count
                $team_total = $call_information->getSlExecutiveTeam($f_date, $l_date, '', $rep_Ids);
                $executiv_disposition = $call_information->getSlExecutiveDisposition($f_date, $l_date, '', $rep_Ids);
                $executiv_sentiment = $call_information->getSlExecutiveSentiment($f_date, $l_date, '', $rep_Ids);
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
            Cache::put($cahce_key, $team_total, 60 * 60);
        } else {
            $team_total = Cache::get($cahce_key);
        }
        //total count of executive report
        $total_count = SalesloftReport::reportTotal($team_total);

        return view('report.executivfilter', ['e_data' => $team_total, 'executive_count' => $total_count])->render();
    }

    // Single Reps Report 
    function slSingleRepReport()
    {
        $get_key = Session::get('key');
        $group_data = getUserpermission($get_key);
        $current_date = date('Y-m-d');
        $f_date = date('Y-m-01', strtotime($current_date));
        $l_date = date('Y-m-d', strtotime("-1 days"));

        // from CacheReports Command
        $repId = Cache::get('reps_' . $group_data['single_group']->id);
        //working days
        if ($repId == null) {
            $repId = [];
        }
        $date1 = date_create($f_date);
        $date2 = date_create($l_date);
        $start = Carbon::parse($date1);
        $end = Carbon::parse($date2);
        $count_days = $start->diffInDaysFiltered(function (Carbon $date) {
            return $date->isWeekday();
        }, $end) + 1;
        //total count of single reps report
        $r_total_count = SalesloftReport::reportTotal($repId);


        return view('report.singlerep', ['r_data' => $repId, 'r_count' => $r_total_count, 't_days' => $count_days]);
    }

    // Single Reps Report Filter
    function singlerepFilter(Request $request)
    {
        $get_key = Session::get('key');
        $group_data = getUserpermission($get_key);
        $reps = isset($group_data['single_group']->sl_user_ids) ? count($group_data['single_group']->sl_user_ids) : 0;
        $rep_Ids = isset($group_data['single_group']->sl_user_ids) ? $group_data['single_group']->sl_user_ids : [];
        $keys = $request->key;

        if ($reps > 0 || Auth::user()->user_role == 'Super Admin') {

            $call_information = new Slcallinfo();
            $all_dates = $call_information->getdate();
            if ($request->starting_date == "" && $request->ending_date == "") {
                $f_date = $all_dates[0]['minDate'];
                $l_date = $all_dates[0]['maxDate'];
            } else {
                $f_date =  $request->starting_date;
                $l_date =  $request->ending_date;
            }

            if ((Auth::user()->user_role == 'Super Admin' && $group_data['single_group']->group_name == 'All') || $group_data['single_group']->group_name == 'All') {
                $cahce_key = $keys;
            } else {
                $cahce_key = $group_data['single_group']->group_name . '_reps_' . $keys . '_' . Auth::user()->id;
            }

            if (Cache::get($cahce_key) == null || $cahce_key == null) {

                if ((Auth::user()->user_role == 'Super Admin' && $group_data['single_group']->group_name == 'All') || $group_data['single_group']->group_name == 'All') {
                    $repId = $call_information->getSingleRepId($f_date, $l_date);
                    //get reps id
                    $rep_Ids = array();
                    foreach ($repId as $key => $value) {
                        $rep_Ids[] = $value->salesloft_user_id;
                    }
                } else {
                    $repId = $call_information->getSingleUserRepId($rep_Ids, $f_date, $l_date);
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

                    //set dails, days, talktime
                    $repId[$key]->repname = isset($rep_details[$value->salesloft_user_id]) ? $rep_details[$value->salesloft_user_id] : 0;

                    //set disposition count
                    $repId[$key]->disposition = isset($disposition_details[$value->salesloft_user_id]) ? $disposition_details[$value->salesloft_user_id] : 0;

                    //set  sentiment count
                    $repId[$key]->sentiment = isset($sentiment_details[$value->salesloft_user_id]) ? $sentiment_details[$value->salesloft_user_id] : 0;
                }

                Cache::put($cahce_key, $repId, 60 * 60);
            } else {
                $repId =  Cache::get($cahce_key);
            }

            //total count of single reps report
            $r_total_count = SalesloftReport::reportTotal($repId);
            //working days
            $date1 = date_create($f_date);
            $date2 = date_create($l_date);
            $start = Carbon::parse($date1);
            $end = Carbon::parse($date2);
            $count_days = $start->diffInDaysFiltered(function (Carbon $date) {
                return $date->isWeekday();
            }, $end) + 1;

            return view('report.singlerepfilter', ['r_data' => $repId, 'r_count' => $r_total_count, 't_days' => $count_days])->render();
        }
    }

    //total count of report data
    function reportTotal($total)
    {
        $decision_total = 0;
        $influencer_total = 0;
        $connection_total = 0;
        $day_total = 0;
        $hookRejected_total = 0;
        $hookAccepted_total = 0;
        $pitchRejected_total = 0;
        $qualifiedPitch_total = 0;
        $demo_total = 0;
        $confirmation_call_total = 0;
        foreach ($total as $data) {
            $days = isset($data->days) ? $data->days : 0;
            $decisionMaker = isset($data->disposition['Decision Maker']) ? $data->disposition['Decision Maker'] : 0;
            $confirmation_call = isset($data->disposition['Confirmation Call']) ? $data->disposition['Confirmation Call'] : 0;
            $influencer = isset($data->disposition['Influencer']) ? $data->disposition['Influencer'] : 0;
            $hookReject = isset($data->sentiment['Hook Rejected']) ? $data->sentiment['Hook Rejected'] : 0;
            $hookAccepted = isset($data->sentiment['Hook Accepted']) ? $data->sentiment['Hook Accepted'] : 0;
            $pitchRejected = isset($data->sentiment['Pitch Rejected']) ? $data->sentiment['Pitch Rejected'] : 0;
            $qualified_Pitch = isset($data->sentiment['Qualified Pitch']) ? $data->sentiment['Qualified Pitch'] : 0;
            $demo = isset($data->sentiment['Demo Scheduled']) ? $data->sentiment['Demo Scheduled'] : 0;

            $day_total = $day_total + $days;
            $decision_total = $decision_total + $decisionMaker;
            $influencer_total = $influencer_total + $influencer;
            $count = $decisionMaker + $influencer;
            $connection_total = $connection_total + $count;
            $hookRejected_total = $hookRejected_total + $hookReject;
            $hookAccepted_total = $hookAccepted_total + $hookAccepted;
            $pitchRejected_total = $pitchRejected_total + $pitchRejected;
            $qualifiedPitch_total = $qualifiedPitch_total + $qualified_Pitch;
            $demo_total = $demo_total + $demo;
            $confirmation_call_total = $confirmation_call_total + $confirmation_call;
        }
        $total_count = array(
            "decision_total" => $decision_total,
            "confirmation_call_total" => $confirmation_call_total,
            "influencer_total" => $influencer_total,
            "connection_total" => $connection_total,
            "day_total" => $day_total,
            "hookRejected_total" => $hookRejected_total,
            "hookAccepted_total" => $hookAccepted_total,
            "pitchRejected_total" => $pitchRejected_total,
            "qualified_total" => $qualifiedPitch_total,
            "demo_total" => $demo_total
        );
        return $total_count;
    }
}
