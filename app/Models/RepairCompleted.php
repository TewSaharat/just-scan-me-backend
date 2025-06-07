<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RepairCompleted extends Model
{
    use HasFactory;

    protected $table = 'repair_completed'; // ชื่อตารางในฐานข้อมูล
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name_id',
        'lamp_type',
        'repairMethod',
        'lastRepairDate',
        'notes',
        'repairItems',
        'cat_id',
        'dir',
        'dir_num',
        'routes',
        'control',
        'km',
        'lat',
        'longitude',
        'fovy',
        'range',
        'status'
    ];
}
