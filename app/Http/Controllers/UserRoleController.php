<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class UserRoleController extends Controller
{
    
    //view user in dashboard and searching
    function viewUsers()
    {
        $user = new User();
        $data = $user->viewUser('', 10);
        return view('user.users', ['data' => $data]);
    }
    //use that ajax call 
    function SviewUsers(Request $req)
    {
        $user = new User();
        if ($req->ajax()) {
            $search = $_GET['search'];
            $limit = $_GET['limit'];
            $data = $user->viewUser($search, $limit);
            return view('user.userdata', ['data' => $data])->render();
        }
    }
    //add role and update role
    function addRole(Request $req)
    {
        $user = new User();
        //update role
        if (isset($req->roleid)) {
            $id = $req->roleid;
            $req->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
            ]);
            $data = [
                'user_role' => $req->userrole,
                'name' => $req->name,
                'email' => $req->email,
            ];
            $user->updateUser($id, $data);
        } 
        return Redirect::route('users')->with([
            'message' => 'User updated successfully!',
            'alert-type' => 'success'
        ]);;
    }
    //delete role or user
    function deleteRole($id)
    {
        $user = new User();
        $data = ['is_delete' => 1];
        $user->updateUser($id, $data);
        return Redirect::route('users')->with([
            'message' => 'User deleted successfully!',
            'alert-type' => 'error'
        ]);;
    }
    //view user-data in update form
    function showUpdateRole($id)
    {
        $data = User::find($id);
        return view('user.addUser', ['data' => $data]);
    }

    function setKey(Request $req)
    {
        $set_key = $req->get('key');
        Session::put('key', $set_key);
        $data = getUserpermission($set_key);
        $cadence_id = isset($data['single_group']->sl_cadence_ids) ? count($data['single_group']->sl_cadence_ids) : 0;
        $reps_id = isset($data['single_group']->sl_user_ids) ? count($data['single_group']->sl_user_ids) : 0;
        if(($cadence_id > 0 && $reps_id > 0) || $data['single_group']->group_name == 'All'){
            return Redirect::back();
        }elseif($reps_id == 0 && $cadence_id > 0){
            return redirect('sl/cadencereport');
        }elseif($cadence_id == 0 && $reps_id > 0){
            return redirect('sl/executivereport');
        }else{
            return view('home');
        }
    }
}
