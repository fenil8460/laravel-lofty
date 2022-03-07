<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Group;
use GuzzleHttp\Client;
use App\Models\GroupCadence;
use App\Models\GroupSlusers;
use App\Models\GroupUsers;

//salesloft call api
function getSalesLoftCall($updateDate, $page, $type, $api_key)
{
    if ($type == 'backword') {
        $response = Http::withToken($api_key)->get('https://api.salesloft.com/v2/activities/calls.json?sort_direction=DESC&per_page=100&page=' . $page . '&include_paging_counts=true&updated_at%5Blte%5D=' . $updateDate . '');
    } else {
        $response = Http::withToken($api_key)->get('https://api.salesloft.com/v2/activities/calls.json?sort_direction=ASC&per_page=100&page=' . $page . '&include_paging_counts=true&updated_at%5Bgt%5D=' . $updateDate . '');
    }
    return $response;
}

//salesloft calldata api
function getSalesLoftCallData($updateDate, $page, $type, $api_key)
{
    if ($type == 'backword') {
        $response = Http::withToken($api_key)->get('https://api.salesloft.com/v2/call_data_records.json?sort_direction=DESC&per_page=100&page=' . $page . '&include_paging_counts=true&updated_at%5Blte%5D=' . $updateDate . '');
    } else {
        $response = Http::withToken($api_key)->get('https://api.salesloft.com/v2/call_data_records.json?sort_direction=ASC&per_page=100&page=' . $page . '&include_paging_counts=true&updated_at%5Bgt%5D=' . $updateDate . '');
    }
    return $response;
}

//salesloft cadence api
function getSalesLoftCadence($updateDate, $page, $type, $api_key)
{
    if ($type == 'backword') {
        $response = Http::withToken($api_key)->get('https://api.salesloft.com/v2/cadences.json?sort_direction=DESC&per_page=100&page=' . $page . '&include_paging_counts=true&updated_at%5Blte%5D=' . $updateDate . '');
    } else {
        $response = Http::withToken($api_key)->get('https://api.salesloft.com/v2/cadences.json?sort_direction=ASC&per_page=100&page=' . $page . '&include_paging_counts=true&updated_at%5Bgt%5D=' . $updateDate . '');
    }
    return $response;
}

//salesloft user api
function getSalesLoftUsers($page, $api_key)
{
    $response = Http::withToken($api_key)->get('https://api.salesloft.com/v2/users.json?&sort_direction=ASC&per_page=100&page=' . $page . '&include_paging_counts=true');
    // active=true
    return $response;
}

//salesloft people api(working leads)
function getSalesLoftPeople1($cadence_id,$api_key)
{
    $response = Http::withToken($api_key)->get('https://api.salesloft.com/v2/people.json?per_page=25&page=1&include_paging_counts=true&cadence_id%5B%5D='.$cadence_id);
    return $response;
}
// function getSalesLoftPeople1($createdDate, $page, $api_key)
// {
//     $response = Http::withToken($api_key)->get('https://api.salesloft.com/v2/people.json?sort_direction=ASC&per_page=100&page=' . $page . '&include_paging_counts=true&created_at%5Bgt%5D=' . $createdDate . '&sort_by=created_at');
//     return $response;
// }

//salesloft people api
function getSalesLoftPeople($cadenceId, $f_date, $l_date, $api_key)
{
    $response = Http::withToken($api_key)->get('https://api.salesloft.com/v2/people.json?include_paging_counts=true&updated_at%5Bgte%5D=' . $f_date . '&updated_at%5Blte%5D=' . $l_date . '&cadence_id%5B%5D=' . $cadenceId . '');
    return $response;
}


// function getUserpermission12($key = 0)
// {
//     if (Auth::user()->user_role == 'Super Admin') {
//         $key_id = isset($key) ? $key : 0;
//         $get_group = Group::all();
//         if (count($get_group) > 0) {
//             foreach ($get_group as $key => $data) {
//                 $get_group[$key]->sl_cadence_ids = array_filter(explode(',', $data->sl_cadence_ids));
//                 $get_group[$key]->sl_user_ids = array_filter(explode(',', $data->sl_user_ids));
//             }
//             if ($get_group != null) {
//                 $datas = [
//                     'single_group' => $get_group[$key_id],
//                     'count' => count($get_group),
//                     'group' => $get_group
//                 ];
//                 return $datas;
//             }
//         }
//     } else {
//         $key_id = isset($key) ? $key : 0;
//         $group = Group::all();
//         $id = Auth::user()->id;
//         $group_id = [];
//         foreach ($group as $data) {
//             $group_id[] = [
//                 'group_id' => $data->id,
//                 'user_id' => explode(',', $data->user_id),
//             ];
//         }
//         $a = [];
//         foreach ($group_id as $key => $data) {
//             if (in_array($id, $data['user_id'])) {
//                 $a[] =  $data['group_id'];
//             }
//         }
//         if (count($a) > 0) {
//             $get_group = Group::whereIN('id', $a)->get();
//             foreach ($get_group as $key => $data) {
//                 $get_group[$key]->sl_cadence_ids = array_filter(explode(',', $data->sl_cadence_ids));
//                 $get_group[$key]->sl_user_ids = array_filter(explode(',', $data->sl_user_ids));
//             }
//             if ($get_group != null) {
//                 $datas = [
//                     'single_group' => $get_group[$key_id],
//                     'count' => count($get_group),
//                     'group' => $get_group
//                 ];
//                 return $datas;
//             }
//         }
//     }
// }


function getUserpermission($key = 0)
{
    
    $key_id = isset($key) ? $key : 0;
    if (Auth::user()->user_role == 'Super Admin') {
        // get user wise group id
        $user_group_id = GroupUsers::select('group_id')->get();
    } else {

        $user_group_id = GroupUsers::select('group_id')
            ->where('user_id', Auth::user()->id)
            ->get();
    }
    if (count($user_group_id) > 0) {
        //get group
        $group = Group::whereIn('id', $user_group_id)->get();

        //get cadence_id
        $group_cadence_id = GroupCadence::selectRaw('group_concat(cadence_id) as cadence_ids,group_id')->whereIn('group_id', $user_group_id)
        ->groupBy('group_id')->get();

        //get sluser id
        $group_sluser_id = GroupSlusers::selectRaw('group_concat(sl_user_id) as slusers_ids,group_id')->whereIn('group_id', $user_group_id)
        ->groupBy('group_id')->get();

        //get perticuler group cadence id and store
        $store_cadence_id = [];
        $all_sl_cadenceIds = '';
        foreach ($group_cadence_id as $data) {
            $store_cadence_id[$data->group_id] = explode(',', $data->cadence_ids);
            $all_sl_cadenceIds .= $data->cadence_ids.',';
        }
        $all_sl_cadenceIds = explode(',',rtrim($all_sl_cadenceIds,','));

        //get perticuler group sl id and store
        $store_sluser_id = [];
        $all_sl_useIds = '';
        foreach ($group_sluser_id as $data) {
            $store_sluser_id[$data->group_id] = explode(',', $data->slusers_ids);
            $all_sl_useIds .= $data->slusers_ids.',';
        }
        $all_sl_useIds = explode(',',rtrim($all_sl_useIds,','));
        foreach ($group as $key => $data) {
            $group[$key]->sl_cadence_ids = isset($store_cadence_id[$data->id]) ? $store_cadence_id[$data->id] : null;
            $group[$key]->sl_user_ids = isset($store_sluser_id[$data->id]) ? $store_sluser_id[$data->id] : null;
        }
        if(!isset($group[$key_id])){
            $key_id = 0;
        }
      
        $g_count = count($group);
        $datas = [
            'single_group' => $group[$key_id],
            'all_cadence_id' => $all_sl_cadenceIds,
            'all_sl_useIds'=> $all_sl_useIds,
            'count' => $g_count,
            'group' => $group
        ];
        return $datas;
    }
}

function groupUpdate($datas)
{
    $group_name = $datas['group_name'];
    $group_cadence_id = isset($datas['sl_cadence_ids']) ? explode(',', $datas['sl_cadence_ids']) : [];
    $group_sluser_id = isset($datas['sl_user_ids']) ? explode(',', $datas['sl_user_ids']) : [];
    $group_user_id = isset($datas['user_id']) ? explode(',', $datas['user_id']) : [];
    $group_id = isset($datas['group_id']) ? $datas['group_id'] : null;

    //group name update
    Group::where('id',$group_id)->update(['group_name'=>$group_name]);

    //cadence update on group
    $cadence_group = GroupCadence::selectRaw('group_concat(cadence_id) as cadence_ids')->where('group_id', $group_id)->groupBy('group_id')->get();
    $tbl_cadence_id = isset($cadence_group[0]->cadence_ids) ? explode(',', $cadence_group[0]->cadence_ids) : [];
    $cadence_add_result=array_diff($group_cadence_id,$tbl_cadence_id);
    $cadence_delete_result=array_diff($tbl_cadence_id,$group_cadence_id);
    foreach ($cadence_add_result as $data) {
        GroupCadence::insert([
                        'group_id' => $group_id,
                        'cadence_id' => $data
                    ]); 
    }
    foreach ($cadence_delete_result as $data){
        GroupCadence::where('cadence_id',$data)->where('group_id',$group_id)->delete();
    }

    //sl_user update
    $sl_user_group = GroupSlusers::selectRaw('group_concat(sl_user_id) as sl_user_ids')->where('group_id', $group_id)->groupBy('group_id')->get();
    $tbl_sluser_id = isset($sl_user_group[0]->sl_user_ids) ? explode(',', $sl_user_group[0]->sl_user_ids) : [];
    $sluser_add_result= array_diff($group_sluser_id,$tbl_sluser_id);
    $sluser_delete_result=array_diff($tbl_sluser_id,$group_sluser_id);
    foreach ($sluser_add_result as $data) {
        GroupSlusers::insert([
                        'group_id' => $group_id,
                        'sl_user_id' => $data
                    ]); 
    }
    foreach ($sluser_delete_result as $data){
        GroupSlusers::where('sl_user_id',$data)->where('group_id',$group_id)->delete();
    }

    //user update
    $user_group = GroupUsers::selectRaw('group_concat(user_id) as user_ids')->where('group_id', $group_id)->groupBy('group_id')->get();
    $tbl_user_id = isset($user_group[0]->user_ids) ? explode(',', $user_group[0]->user_ids) : [];
    $user_add_result=array_diff($group_user_id,$tbl_user_id);
    $user_delete_result=array_diff($tbl_user_id,$group_user_id);
    foreach ($user_add_result as $data) {
        GroupUsers::insert([
                        'group_id' => $group_id,
                        'user_id' => $data
                    ]); 
    }
    foreach ($user_delete_result as $data){
        GroupUsers::where('user_id',$data)->where('group_id',$group_id)->delete();
    }
}
