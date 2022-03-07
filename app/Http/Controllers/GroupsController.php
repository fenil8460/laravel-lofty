<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupCadence;
use App\Models\GroupSlusers;
use App\Models\GroupUsers;
use App\Models\User;
use App\Models\salesloft\Slcadence;
use App\Models\salesloft\Slusers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class GroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = new Group;
        $users = new User;
        $listgroup = $groups->listgroup('', 10);
        
        //get user id and store in variable
        $get_group_user = GroupUsers::selectRaw('group_id,group_concat(user_id) as user_ids')->groupBy('group_id')->get();
        $store_group_user = [];
        foreach ($get_group_user as $data) {
            $store_group_user[$data->group_id] = explode(',', $data->user_ids);
        }
        foreach ($listgroup as $key => $data) {
            $listgroup[$key]->user_id = isset($store_group_user[$data->id]) ? $store_group_user[$data->id] : [];
            $user = $users->getUsername($data->user_id);
            $listgroup[$key]->user_name = isset($user[0]->username) ? $user[0]->username : "";
        }
        return view('groups.listgroup', ['listgroup' => $listgroup]);
    }

    //use that ajax call 
    function searchGroups(Request $req)
    {
        $groups = new Group;
        $users = new User;
        if ($req->ajax()) {
            $search = $_GET['search'];
            $limit = $_GET['limit'];
            $listgroup = $groups->listgroup($search, $limit);
            $get_group_user = GroupUsers::selectRaw('group_id,group_concat(user_id) as user_ids')->groupBy('group_id')->get();
            $store_group_user = [];
            foreach ($get_group_user as $data) {
                $store_group_user[$data->group_id] = explode(',', $data->user_ids);
            }
            foreach ($listgroup as $key => $data) {
                $listgroup[$key]->user_id = isset($store_group_user[$data->id]) ? $store_group_user[$data->id] : null;
                $user = $users->getUsername($data->user_id);
                $listgroup[$key]->user_name = isset($user[0]->username) ? $user[0]->username : "";
            }
            return view('groups.group', ['listgroup' => $listgroup])->render();
        }
    }

    //get user 
    public function userFetch(Request $request)
    {
        $users = new User();
        $query = $request->get('term');
        $data = $users->fetchUserName($query);
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $slcadence = new Slcadence;
        $slusers = new Slusers;
        $users = new User;

        $cadence = $slcadence->getCadences();
        $slusers = $slusers->getslUsers();
        $users = $users->getUsers();
        $data = [
            'users' => $users,
            'cadences' => $cadence,
            'slusers' => $slusers
        ];
        return view('groups.addgroup', ['g_data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $groups = new Group;
        $request->validate(
            [
                'user_name' => 'required|nullable'
            ],
            ['user_name.required' => 'The UserName is required.']
        );

        $groups->group_name = $request->group_name;
        $groups->save();
        //inser data in grup_cadence table
        if (isset($request->cadence)) {
            foreach ($request->cadence as $cadence) {
                GroupCadence::insert([
                    'group_id' => $groups->id,
                    'cadence_id' => $cadence
                ]);
            }
        }

        //inser data in grup_user table
        if (isset($request->user_name)) {
            foreach ($request->user_name as $user_name) {
                GroupUsers::insert([
                    'group_id' => $groups->id,
                    'user_id' => $user_name
                ]);
            }
        }

        //inser data in grup_sl_user table
        if (isset($request->user_reps)) {
            foreach ($request->user_reps as $user_reps) {
                GroupSlusers::insert([
                    'group_id' => $groups->id,
                    'sl_user_id' => $user_reps
                ]);
            }
        }

        return Redirect::route('list-group')->with([
            'message' => 'Group created successfully!',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $users = new User;
        $listgroup = Group::where('id', $id)->get();
        //get user
        $group_user_id = GroupUsers::selectRaw('group_concat(user_id) as user_ids,group_id')
            ->where('group_id', $id)
            ->get();

        //get cadence_id
        $group_cadence_id = GroupCadence::selectRaw('group_concat(cadence_id) as cadence_ids,group_id')->where('group_id', $id)
            ->groupBy('group_id')->get();

        //get sluser id
        $group_sluser_id = GroupSlusers::selectRaw('group_concat(sl_user_id) as slusers_ids,group_id')->where('group_id', $id)
            ->groupBy('group_id')->get();

        //get perticuler group user id and store
        $store_user_id = [];
        foreach ($group_user_id as $data) {
            $store_user_id[$data->group_id] = explode(',', $data->user_ids);
        }
        
        //get perticuler group cadence id and store
        $store_cadence_id = [];
        foreach ($group_cadence_id as $data) {
            $store_cadence_id[$data->group_id] = explode(',', $data->cadence_ids);
        }

        //get perticuler group sl id and store
        $store_sluser_id = [];
        foreach ($group_sluser_id as $data) {
            $store_sluser_id[$data->group_id] = explode(',', $data->slusers_ids);
        }

        $slcadence = new Slcadence;
        $slusers = new Slusers;

        $cadence = $slcadence->getCadences();
        $salesusers = $slusers->getslUsers();
        $users = $users->getUsers();
        $datas = [
            'users' => $users,
            'cadences' => $cadence,
            'slusers' => $salesusers
        ];
        //set data in group array
        foreach ($listgroup as $key => $data) {
            $listgroup[$key]->cadence_ids = isset($store_cadence_id[$data->id]) ? $store_cadence_id[$data->id] : null;
            $listgroup[$key]->reps_ids = isset($store_sluser_id[$data->id]) ? $store_sluser_id[$data->id] : null;
            $listgroup[$key]->user_id = isset($store_user_id[$data->id]) ? $store_user_id[$data->id] : null;
        }
        return view('groups.addgroup', ['data' => $listgroup, 'g_data' => $datas]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate(
            [
                'user_name' => 'required|nullable'
            ],
            ['user_name.required' => 'The UserName is required.']
        );
        $data = [
            'group_name' => $request->group_name,
            'sl_cadence_ids' => isset($request->cadence) ? implode(',', $request->cadence) : null,
            'sl_user_ids' => isset($request->user_reps) ? implode(',', $request->user_reps) : null,
            'user_id' => isset($request->user_name) ? implode(',', $request->user_name) : null,
            'group_id' => $request->group_id
        ];

        //helper function
        groupUpdate($data);
        
        return Redirect::route('list-group')->with([
            'message' => 'Group updated successfully!',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Session::forget('key');
        Group::where('id', $id)->delete();
        GroupCadence::where('group_id', $id)->delete();
        GroupUsers::where('group_id', $id)->delete();
        GroupSlusers::where('group_id', $id)->delete();
        return Redirect::back()->with([
            'message' => 'Group deleted successfully!',
            'alert-type' => 'error'
        ]);
    }
}
