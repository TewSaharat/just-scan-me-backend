<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'cat_id', 'lamp_type', 'dir', 'dir_num', 'routes', 'control', 'km',
        'lat', 'longitude', 'fovy', 'ranges', 'name_id', 'status',
        'complaintReason', 'report_time'
    ];
}
