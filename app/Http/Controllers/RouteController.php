<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\NotifyExport;
use App\Exports\RepairExport;


class RouteController extends Controller
{
    //  API: ดึงเส้นทางที่ status = 0 get-routes`)
    public function getRoutes()
    {
        $routes = DB::table('routes')
            ->select(
                'cat_id', 'lamp_type', 'dir', 'dir_num', 'routes', 'control', 'km',
                'lat', 'longitude', 'fovy', 'ranges', 'name_id', 'status', 'complaintReason', 'report_time','current'
            )
            ->where('status', 0)
            ->get();

        return response()->json($routes);
    }

    //  API: ดึงเส้นทางที่มี filter
    public function getFilteredRoutes(Request $request)
    {
        $query = DB::table('routes')
            ->select('lat', 'longitude', 'name_id', 'routes', 'cat_id', 'status', 'complaintReason', 'report_time','control','lastRepairDate','current');

        if ($request->has('category') && $request->category !== 'all') {
            $query->where('cat_id', $request->category);
        }
        if ($request->has('routes') && $request->routes !== 'all') {
            $query->where('routes', $request->routes);
        }

        $routes = $query->get();
        return response()->json($routes);
    }

    //  API: ดึงข้อมูลของหมุด 
    public function getMarker($name_id)
    {
        $route = DB::table('routes')->where('name_id', $name_id)->first();

        if (!$route) {
            return response()->json(['error' => 'Marker not found'], 404);
        }

        return response()->json($route);
    }

    //  API: อัปเดตข้อมูลเสาไฟฟ้า (เหมือน `/api/save-electric-pole`)
public function saveElectricPole(Request $request)
{
    $validated = $request->validate([
        'name_id' => 'required',
    ]);

    $statusValue = $request->status == true || $request->status == 1 ? 1 : 0;

    // Update 'routes'
    $affected = DB::table('routes')
        ->where('name_id', $request->name_id)
        ->update([
            'lampType_edit' => $request->lampType,
            'controller_edit' => $request->controller_edit,
            'constructionDate' => $request->constructionDate,
            'contractNumber' => $request->contractNumber,
            'notes' => $request->notes,
            'status' => $statusValue,
            'repairMethod' => $request->repairMethod,
            'complaintChannel' => $request->complaintChannel,
            'complaintCode' => $request->complaintCode,
            'complaintTopic' => $request->complaintTopic,
            'complaintReason' => $request->complaintReason,
            'lastRepairDate' => $request->lastRepairDate,
            'controlType' => $request->controlType,
            'repairItems' => $request->repairItems,
            'report_time' => $request->report_time,
        ]);

    if (!$affected) {
        return response()->json(['error' => 'Failed to update data'], 500);
    }

    $row = DB::table('routes')->where('name_id', $request->name_id)->first();

    if (!$row) {
        return response()->json(['error' => "No data found for name_id: $request->name_id"], 404);
    }

    $time = now()->format('d-m-Y H:i');

    // แปลง cat_id เป็นชื่อหมวด
    $catMap = [
        1 => 'หมวดทางหลวงพนัสนิคม',
        2 => 'หมวดทางหลวงบ้านบึง',
        3 => 'หมวดทางหลวงศรีราชา',
        4 => 'หมวดทางหลวงบางละมุง',
        5 => 'หมวดทางหลวงสัตหีบ',
        6 => 'หมวดทางหลวงเมืองชลบุรี',
    ];
    $catName = $catMap[$row->cat_id] ?? 'ไม่ทราบหมวด';

    if ($statusValue === 1) {
        // Insert into repair_completed
        DB::table('repair_completed')->insert([
            'name_id' => $row->name_id,
            'lampType' => $row->lamp_type,
            'repairMethod' => $row->repairMethod,
            'lastRepairDate' => $row->lastRepairDate,
            'notes' => $row->notes,
            'repairItems' => $row->repairItems ?? '{}',
            'cat_id' => $catName, // แปลงเป็นชื่อหมวด
            'dir' => $row->dir,
            'dir_num' => $row->dir_num,
            'routes' => $row->routes,
            'control' => $row->control,
            'km' => $row->km,
            'lat' => $row->lat,
            'longitude' => $row->longitude,
            'fovy' => $row->fovy,
            'ranges' => $row->ranges,
            'status' => $row->status,
        ]);

        return response()->json(['message' => 'Data saved successfully in Repair_completed.']);
    } else {
        // Insert into notify
        DB::table('notify')->insert([
            'lamp_type' => $row->lamp_type,
            'dir' => $row->dir,
            'dir_num' => $row->dir_num,
            'routes' => $row->routes,
            'control' => $row->control,
            'km' => $row->km,
            'lat' => $row->lat,
            'longitude' => $row->longitude,
            'fovy' => $row->fovy,
            'ranges' => $row->ranges,
            'name_id' => $request->name_id,
            'status' => $statusValue,
            'complaintReason' => $row->complaintReason ?? 'No complaint',
            'report_time' => $time,
            'cat_id' => $catName, // เพิ่มใน notify เช่นกัน
        ]);

        return response()->json(['message' => 'Data saved successfully in notify.']);
    }
}

}
