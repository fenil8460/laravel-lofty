<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupCadence extends Model
{
    use HasFactory;
    protected $table = 'group_cadences';

    protected $fillable = [
        'group_id',
        'cadence_id',
    ];
}
