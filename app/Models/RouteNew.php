<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteNew extends Model
{
    use HasFactory;

    protected $table = 'routes';

    protected $fillable = [
        'cat_id',
        'lamp_type',
        'dir',
        'dir_num',
        'routes',
        'control',
        'km',
        'lat',
        'longitude',
        'fovy',
        'ranges',
        'name_id',
        'status',
        'lampType_edit',
        'controller_edit',
        'constructionDate',
        'contractNumber',
        'notes',
        'repairMethod',
        'complaintChannel',
        'complaintCode',
        'complaintTopic',
        'complaintReason',
        'repairParts',
        'controlType',
        'lastRepairDate',
        'report_time',
    ];
}
