<?php

namespace App\Models\salesloft;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slcadence extends Model
{
    use HasFactory;
    protected $table = 'sl_cadence';

    protected $fillable = [
        'sl_cadence_id',
        'salesloft_created_at',
        'salesloft_updated_at',
        'archived_at',
        'team_cadence',
        'shared',
        'remove_bounces_enabled',
        'remove_replies_enabled',
        'opt_out_link_included',
        'draft',
        'cadence_framework_id',
        'cadence_function',
        'name',
        'tags',
        'creator_id',
        'creator_href',
        'owner_id',
        'owner_href',
        'bounced_stage_id',
        'bounced_stage_href',
        'replied_stage_id',
        'replied_stage_href',
        'added_stage_id',
        'added_stage_href',
        'finished_stage_id',
        'finished_stage_href',
        'cadence_priority_id',
        'cadence_priority_href',
        'counts_cadence_people',
        'counts_people_acted_on_count',
        'counts_target_daily_people',
        'counts_opportunities_created',
        'counts_meetings_booked',
        'salesLoft_account_id',
    ];

    public function getSlCadenceName($id)
    {
        $candence_name = Slcadence::select('name', 'sl_cadence_id')
            ->whereIn('sl_cadence_id', $id)
            ->get();
        return $candence_name;
    }

    public function getCadences()
    {
        $data = Slcadence::select('name', 'sl_cadence_id')
            ->get();
        return $data;
    }

    public function getGroupCadence($id)
    {
        $data = Slcadence::selectRaw('group_concat(name) as cadece_name')
            ->whereIn('sl_cadence_id', $id)
            ->get();
        return $data;
    }
}
