<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'groups_name',
        'campaigns',
        'reps',
        'user_id',
    ];

    //list group
    public function listgroup($search, $limit)
    {
        $userdata = Group::where('group_name', 'LIKE', '%' . $search . '%')
            ->paginate($limit);
        return $userdata;
    }

    //add group
    public function addGroup($data){
        Group::insert($data);
    }

    //update group
    public function updateGroup($id,$data){
        Group::where('id', $id)->update($data);
    }
}
