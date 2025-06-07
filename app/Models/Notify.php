<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notify extends Model
{
    use HasFactory;

    protected $table = 'notify'; // ชื่อตารางในฐานข้อมูล
    protected $primaryKey = 'id'; // คีย์หลัก (ถ้ามี)
    public $timestamps = false; // ปิด timestamps ถ้าไม่ต้องการ created_at, updated_at

    protected $fillable = [
        'lamp_type',
        'dir',
        'dir_num',
        'routes',
        'control',
        'km',
        'lat',
        'longitude',
        'fovy',
        'range',
        'name_id',
        'status',
        'lampType_edit',
        'controller_edit',
        'constructionDate',
        'contractNumber',
        'repairMethod',
        'complaintChannel',
        'complaintCode',
        'complaintTopic',
        'complaintReason',
        'repairItems',
        'controlType',
        'lastRepairDate',
        'report_time'
    ];
}
