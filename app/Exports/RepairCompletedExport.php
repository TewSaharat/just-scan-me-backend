<?php

namespace App\Exports;

use App\Models\RepairCompleted;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RepairCompletedExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return RepairCompleted::select([
            'cat_id', 'lamptype', 'dir', 'dir_num', 'routes', 'control', 'km', 
            'lat', 'longitude', 'fovy', 'ranges', 'name_id', 'status', 
            'lampType_edit', 'controller_edit', 'constructionDate', 
            'contractNumber', 'notes', 'repairMethod', 'complaintChannel', 
            'complaintCode', 'complaintTopic', 'complaintReason', 'repairItems', 
            'controlType', 'lastRepairDate', 'report_time'
        ])->get();
    }

    public function headings(): array
    {
        return [
            'Category ID', 'Lamp Type', 'Direction', 'Direction Number', 'Routes', 
            'Control', 'KM', 'Latitude', 'Longitude', 'Field of View', 'Range', 
            'Name ID', 'Status', 'Lamp Type Edit', 'Controller Edit', 
            'Construction Date', 'Contract Number', 'Notes', 'Repair Method', 
            'Complaint Channel', 'Complaint Code', 'Complaint Topic', 
            'Complaint Reason', 'Repair Items', 'Control Type', 'Last Repair Date', 
            'Report Time'
        ];
    }
}
