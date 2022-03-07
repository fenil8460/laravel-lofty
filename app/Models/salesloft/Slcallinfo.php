<?php

namespace App\Models\salesloft;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class Slcallinfo extends Model
{
    use HasFactory;
    protected $table = 'sl_call_informations';

    protected $fillable = [
        'call_id',
        'to',
        'duration',
        'sentiment',
        'disposition',
        'salesloft_created_at',
        'salesloft_updated_at',
        'recordings',
        'user_href',
        'salesloft_user_id',
        'action_id',
        'called_person_href',
        'called_person_id',
        'crm_activity_href',
        'crm_activity_id',
        'note_href',
        'cadence_href',
        'cadence_id',
        'step_href',
        'step_id',
        'parent_id',
        'direction',
        'status',
        'call_type',
        'call_uuid',
        'call_href',
        'salesLoft_account_id'
    ];

    //get perticuler user call record
    public function getSlUserCall($firstdate, $lastdate)
    {
        $slcalluser = Slcallinfo::whereNull('call_type')
            ->where('salesloft_user_id', 23669)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '>=', $firstdate)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '<=', $lastdate)
            ->count();
        //'%Y-%m-%dT%H:%i:%s'
        return $slcalluser;
    }

    //get perticluer user calldata record
    public function getSlUserCalldata($firstdate, $lastdate)
    {
        $slusercalldata = Slcallinfo::whereNotNull('call_type')
            ->where('salesloft_user_id', 23669)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '>=', $firstdate)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '<=', $lastdate)
            ->where('direction', 'outbound')
            ->count();
        //'%Y-%m-%dT%H:%i:%s'
        return $slusercalldata;
    }

    //Data Filter 
    public function getdate()
    {
        $data = Slcallinfo::selectRaw("Min(salesloft_created_at) as minDate2,Max(salesloft_created_at) as maxDate2,Min(STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')) as minDate,
        Max(STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')) as maxDate")->get();
        return $data;
    }
    //cadencereport in get name 
    public function getSlCadenceName($f_date, $l_date)
    {
        $dails = Slcallinfo::selectRaw('cadence_id,salesLoft_account_id')
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '>=', $f_date)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '<=', $l_date)
            ->whereNotNull('call_id')
            ->whereNotNull('cadence_id')
            ->groupBy('cadence_id')
            ->orderBy('cadence_id')
            ->get();
        return $dails;
    }

    //
    public function getSlCadence($cadence_id, $f_date, $l_date)
    {
        $dails = Slcallinfo::selectRaw('cadence_id,salesLoft_account_id')
            ->whereIn('cadence_id', $cadence_id)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '>=', $f_date)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '<=', $l_date)
            ->whereNotNull('call_id')
            ->groupBy('cadence_id')
            ->orderBy('cadence_id')
            ->get();
        return $dails;
    }

    //cadencereport in get disposition count
    public function getSlDisposition($cadence_id, $f_date, $l_date)
    {
        $cadence_disposition = Slcallinfo::selectRaw('cadence_id,disposition,count(disposition) as disposition_count')
            ->whereIn('cadence_id', $cadence_id)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '>=', $f_date)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '<=', $l_date)
            ->whereNotNull('call_id')
            ->orderBy('cadence_id')
            ->groupBy('cadence_id', 'disposition')->get();
        return $cadence_disposition;
    }

    //cadencereport in get sentiment count
    public function getSlSentiment($cadence_id, $f_date, $l_date)
    {
        $cadence_sentiment = Slcallinfo::selectRaw('cadence_id,sentiment,count(sentiment) as sentiment_count')
            ->whereIn('cadence_id', $cadence_id)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '>=', $f_date)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '<=', $l_date)
            ->whereNotNull('call_id')
            ->orderBy('cadence_id')
            ->groupBy('cadence_id', 'sentiment')->get();
        return $cadence_sentiment;
    }
    //cadencereport in get dails
    public function getSlDails($cadence_id, $f_date, $l_date)
    {
        $datas = DB::select(DB::raw('SELECT t1.cadence_id,COUNT(ci.call_id) AS total from sl_call_informations AS ci,(SELECT distinct call_id,cadence_id FROM sl_call_informations WHERE cadence_id IN(' . $cadence_id . ')) AS t1 WHERE t1.call_id = ci.call_id AND call_type IS NOT NULL AND STR_TO_DATE(salesloft_created_at, "%Y-%m-%d") >= "' . $f_date . '" AND STR_TO_DATE(salesloft_created_at, "%Y-%m-%d") <= "' . $l_date . '" GROUP BY cadence_id'));
        return $datas;
    }

    //executivreport in get date,dails,day and talktime
    public function getSlExecutiveTeam($f_date, $l_date, $flag, $repId = null)
    {
        if ($flag == 1) {
            $query = "DATE_FORMAT(STR_TO_DATE(salesloft_created_at, '%Y-%m-%d'),'%M %Y') as team_total";
        } else {
            $query = "STR_TO_DATE(salesloft_created_at, '%Y-%m-%d') as team_total";
        }
        $team_total = Slcallinfo::selectRaw("count(call_id) as dails," . $query . ",count(DISTINCT STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')) as days,Round(sum(duration)) as talktime")
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '>=', $f_date)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '<=', $l_date)
            ->whereNotNull('call_type')
            ->orderBy('salesloft_created_at', 'asc')
            ->groupBy('team_total');
        if ($repId != null) {
            $team_total->whereIn('salesloft_user_id', $repId);
        }

        $team_total =  $team_total->get();
        return $team_total;
    }

    //executivreport in get disposition count
    public function getSlExecutiveDisposition($f_date, $l_date, $flag, $repId = null)
    {
        if ($flag == 1) {
            $query = "DATE_FORMAT(STR_TO_DATE(salesloft_created_at, '%Y-%m-%d'),'%M %Y') as team_total";
        } else {
            $query = "STR_TO_DATE(salesloft_created_at, '%Y-%m-%d') as team_total";
        }
        $executiv_disposition = Slcallinfo::selectRaw("disposition,count(disposition) as disposition_count," . $query . "")
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '>=', $f_date)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '<=', $l_date)
            ->orderBy('salesloft_created_at', 'asc')
            ->groupBy('team_total', 'disposition');
        if ($repId != null) {
            $executiv_disposition->whereIn('salesloft_user_id', $repId);
        }
        $executiv_disposition = $executiv_disposition->get();
        return $executiv_disposition;
    }

    //executivreport in get sentiment count
    public function getSlExecutiveSentiment($f_date, $l_date, $flag, $repId = null)
    {
        if ($flag == 1) {
            $query = "DATE_FORMAT(STR_TO_DATE(salesloft_created_at, '%Y-%m-%d'),'%M %Y') as team_total";
        } else {
            $query = "STR_TO_DATE(salesloft_created_at, '%Y-%m-%d') as team_total";
        }
        $executiv_sentiment = Slcallinfo::selectRaw("sentiment,count(sentiment) as sentiment_count," . $query . "")
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '>=', $f_date)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '<=', $l_date)
            ->orderBy('salesloft_created_at', 'asc')
            ->groupBy('team_total', 'sentiment');

        if ($repId != null) {
            $executiv_sentiment->whereIn('salesloft_user_id', $repId);
        }
        $executiv_sentiment = $executiv_sentiment->get();
        return $executiv_sentiment;
    }

    //get single repsId
    public function getSingleRepId($f_date, $l_date)
    {
        $peopleId = Slcallinfo::selectRaw('(select name from sl_users where sl_users.salesloft_user_id = sl_call_informations.salesloft_user_id limit 1) as name,salesloft_user_id')
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '>=', $f_date)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '<=', $l_date)
            ->whereNull('call_type')
            ->groupBy('salesloft_user_id')
            ->get();
        return $peopleId;
    }

    //get single repsId
    public function getSingleUserRepId($id, $f_date, $l_date)
    {
        $data = Slcallinfo::selectRaw('(select name from sl_users where sl_users.salesloft_user_id = sl_call_informations.salesloft_user_id limit 1) as name,salesloft_user_id')
            ->whereIn('salesloft_user_id', $id)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '>=', $f_date)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '<=', $l_date)
            ->whereNull('call_type')
            ->groupBy('salesloft_user_id')
            ->get();
        return $data;
    }


    //get single rep in get rep name,dails,day and talktime
    public function getSlSingleRepName($id, $f_date, $l_date)
    {
        $rep_name = Slcallinfo::selectRaw("salesloft_user_id,count(call_id) as dails,count(DISTINCT STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')) as days,Round(sum(duration)) as talktime")
            ->whereIn('salesloft_user_id', $id)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '>=', $f_date)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '<=', $l_date)
            ->whereNotNull('call_type')
            // ->orderBy('salesloft_created_at','asc')
            ->groupBy('salesloft_user_id')
            ->get();
        return $rep_name;
    }

    //Single Rep report in get disposition count
    public function getSlSingleRepDisposition($id, $f_date, $l_date)
    {
        $singlerep_disposition = Slcallinfo::selectRaw("salesloft_user_id,disposition,count(disposition) as disposition_count")
            ->whereIn('salesloft_user_id', $id)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '>=', $f_date)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '<=', $l_date)
            ->whereNull('call_type')
            ->groupBy('salesloft_user_id', 'disposition')
            ->get();
        return $singlerep_disposition;
    }

    //Single Rep report in get sentiment count
    public function getSlSingleRepSentiment($id, $f_date, $l_date)
    {
        $singlerep_sentiment = Slcallinfo::selectRaw("salesloft_user_id,sentiment,count(sentiment) as sentiment_count")
            ->whereIn('salesloft_user_id', $id)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '>=', $f_date)
            ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '<=', $l_date)
            ->whereNull('call_type')
            ->groupBy('salesloft_user_id', 'sentiment')
            ->get();
        return $singlerep_sentiment;
    }
}
