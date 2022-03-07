<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spark\Billable;

class User extends Authenticatable
{
    use Billable, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //view and search user
    public function viewUser($search,$limit){
       $userdata = User::where('is_delete','==',0)
                        ->where(function($query) use ($search) {
                            $query->where('name', 'LIKE', '%'.$search.'%')
                                  ->orwhere('email', 'LIKE', '%'.$search.'%');
                        })
                        ->paginate($limit);
       return $userdata;
    }
    
    //adduser
    public function addUser($data){
        $adduser = User::insert($data);
        return $adduser;
    }

    //update user
    public function updateUser($id,$data){
        $updateuser = User::where('id',$id)->update($data);
        return $updateuser;
    }

    //fetch user
    public function fetchUserName($name){
        $data = User::selectRaw('id,name as label,name as value')
        ->where("name",'LIKE',"%{$name}%")
        ->where('is_delete','==',0)
        ->get();
        return $data;
    }

    //get username
    public function getUsername($id){
        $data = User::whereIN('id', $id)->selectRaw('group_concat(name) as username')->get();
        return $data;
    }

    // get user Id
    public function getUserId($name){
        $data = User::whereIN('name', $name)->selectRaw('group_concat(id) as user_id')->get();
        return $data;
    }

    //get user
    public function getUsers(){
        $data = User::select('id','name')->where('is_delete','==',0)->where('user_role','!=','Super Admin')->get();
        return $data;
    }


}
