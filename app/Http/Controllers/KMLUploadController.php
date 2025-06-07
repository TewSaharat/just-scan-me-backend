<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RouteNew;
use Illuminate\Support\Facades\Storage;
use SimpleXMLElement;
use Illuminate\Support\Facades\Log;

class KMLUploadController extends Controller
{
    public function upload(Request $request)
    {
        set_time_limit(1200);
        Log::info('🚀 Upload API called.');
        Log::info('📌 Received cat_id: ' . ($request->cat_id ?? 'ไม่มีค่า'));

        if (!$request->hasFile('file')) {
            Log::error('❌ No file received.');
            return response()->json(['message' => 'ไม่พบไฟล์ที่อัปโหลด'], 422);
        }

        $file = $request->file('file');
        Log::info('✅ Received file: ' . $file->getClientOriginalName());
        Log::info('✅ File MIME Type: ' . $file->getMimeType());

        $request->validate([
            'file' => 'required|file|mimes:kml,xml,text/xml',
            'cat_id' => 'required|integer'
        ]);

        if (!Storage::exists('private/kml_files')) {
            Storage::makeDirectory('private/kml_files');
            Log::info(' Created missing directory: private/kml_files');
        }

        $fileName = $file->getClientOriginalName();
        $filePath = 'private/kml_files/' . $fileName;
        Storage::putFileAs('private/kml_files', $file, $fileName);

        $fullFilePath = Storage::path($filePath);
        Log::info(' Stored file path: ' . $fullFilePath);

        if (!Storage::exists($filePath)) {
            Log::error('❌ File not found in Laravel Storage: ' . $fullFilePath);
            return response()->json(['message' => 'ไฟล์ที่อัปโหลดไม่สามารถเข้าถึงได้จาก Laravel Storage'], 500);
        }

        $xmlContent = file_get_contents($fullFilePath);
        if (!$xmlContent) {
            Log::error('❌ Failed to read KML file.');
            return response()->json(['message' => 'ไม่สามารถอ่านไฟล์ KML ได้'], 500);
        }

        $xml = new SimpleXMLElement($xmlContent);
        Log::info(' XML Parsed Successfully');

        $last_lamp_type = null; // เก็บ lamp_type ล่าสุดที่พบ
        $valid_lamp_types = ['DB', 'SG', 'LT', 'CL']; // รายการ lamp_type ที่ยอมรับ

        foreach ($xml->Document->Placemark as $placemark) {
            $name = trim((string) $placemark->name);
            $coordinates = (string) $placemark->Point->coordinates ?? null;

            if ($coordinates) {
                list($long, $lat, $alt) = explode(',', trim($coordinates));
            } else {
                $lat = null;
                $long = null;
            }

            $cleaned_name = preg_replace('/\s*-+\s*/', '-', preg_replace('/\s+/', ' ', $name));
            $name_parts = preg_split('/[-\s]+/', $cleaned_name);

            if (count($name_parts) < 4) {
                Log::warning("❌ Invalid name format (too few parts), skipping: $name");
                continue;
            }

            if (count($name_parts) == 4) {
                list($dir_id, $routes, $control, $km) = $name_parts;
                if (preg_match('/([A-Z]+)(\d+)([A-Z]+)/i', $dir_id, $matches)) {
                    $dir = strtoupper($matches[1]); // MD
                    $lamp_type = strtoupper($matches[3]); // DB
                    $dir_num = $matches[2]; // 396
                } elseif ($last_lamp_type) {
                    $lamp_type = $last_lamp_type; // ใช้ lamp_type ก่อนหน้า
                } else {
                    Log::warning("❌ Missing lamp_type and no previous value, skipping: $name");
                    continue;
                }
            } else {
                list($dir_id, $lamp_type, $routes, $control, $km) = $name_parts;
                $last_lamp_type = strtoupper($lamp_type); // อัปเดต lamp_type ล่าสุด
                $dir = strtoupper(preg_replace('/[^A-Za-z0-9\/]/', '', $dir_id));
                $dir_num = preg_replace('/[^0-9]/', '', $dir_id);
            }

            if (!is_numeric($routes) || !is_numeric($control)) {
                Log::warning("❌ Skipping invalid name_id format: $name");
                continue;
            }

            $ranges = preg_replace('/[^0-9]/', '', $km);

            $exists = RouteNew::where('name_id', $name)->exists();
            if ($exists) {
                Log::warning("❌ Duplicate entry name_id: $name, skipping");
                continue;
            }

            RouteNew::create([
                'cat_id' => $request->cat_id,
                'lamp_type' => $lamp_type,
                'dir' => $dir,
                'dir_num' => $dir_num,
                'routes' => (int) $routes,
                'control' => (int) $control,
                'km' => $km,
                'lat' => $lat,
                'longitude' => $long,
                'fovy' => 35,
                'ranges' => (float) $ranges,
                'name_id' => $name,
                'status' => 1,
            ]);
        }

        return response()->json(['message' => 'KML file uploaded and processed successfully']);
    }
}
