<?php

namespace App\Models\salesloft;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Slworkingleads extends Model
{
    use HasFactory;
    protected $table = 'sl_working_leads';

    protected $fillable = [
        'people_id',
        'salesloft_created_at',
        'salesloft_updated_at',
        'most_recent_cadence_id',
        'salesLoft_account_id',
    ];

    //cadencereport in get working leads
    public function getWorkingleads($cadence_id, $f_date = null, $l_date = null)
    {
        $date = Carbon::now();
        $leads = Slworkingleads::selectRaw('sl_cadence_id,Max(lead_counts) as wc_count')
        ->whereIn('sl_cadence_id', $cadence_id)
        ->groupBy('sl_cadence_id');
        if($f_date != null && $l_date != null){
            $leads->where('date', '>=', $f_date)
            ->where('date', '<=', $l_date);
        }else{
            $leads->where('date', '=',  $date->format('Y-m-d'));
        }
        $leads = $leads->get();
        return $leads;
        // $leads = Slworkingleads::selectRaw('most_recent_cadence_id,COUNT(*) as working_leads')
        // ->whereIn('most_recent_cadence_id', $cadence_id)
        // ->groupBy('most_recent_cadence_id');
        // if($f_date != null && $l_date != null){
        //     $leads->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '>=', $f_date)
        //     ->where(DB::raw("STR_TO_DATE(salesloft_created_at, '%Y-%m-%d')"), '<=', $l_date);
        // }
        // $leads = $leads->get();
        // return $leads;
    }
}
