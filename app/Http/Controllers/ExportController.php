<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NotifyExport;
use App\Exports\RepairCompletedExport;

class ExportController extends Controller
{
    // Export Notify Table
    public function exportNotifyToExcel()
    {
        
        return Excel::download(new NotifyExport, 'notify_data.xlsx');
    }

    // Export Repair Completed Table
    public function exportRepairToExcel()
    {
        return Excel::download(new RepairCompletedExport, 'repair_completed.xlsx');
    }
}
