<?php

namespace App\Models\salesloft;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slcredential extends Model
{
    use HasFactory;
    protected $table = 'sl_credentials';

    protected $fillable = [
        'instance_name',
        'api_key',
        'active',
        'last_record_founded_datetime',
    ];


}
