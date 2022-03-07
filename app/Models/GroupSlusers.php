<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupSlusers extends Model
{
    use HasFactory;
    protected $table = 'group_sl_users';

    protected $fillable = [
        'group_id',
        'sl_user_id',
    ];

}
