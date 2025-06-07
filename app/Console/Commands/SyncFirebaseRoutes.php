<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class SyncFirebaseRoutes extends Command
{
    protected $signature = 'sync:firebase-routes';
    protected $description = 'Sync data from Firebase to routes table every 3 minutes';

    public function handle(): void
    {
        $url = 'https://just-scan-me-default-rtdb.asia-southeast1.firebasedatabase.app/Data.json';
        $response = Http::get($url);

        if (!$response->ok()) {
            $this->error('Failed to fetch data');
            return;
        }

        $data = $response->json();

        if (!$data) {
            $this->error('No data received');
            return;
        }

        foreach ($data as $sender => $entry) {
            $name_id = match($sender) {
                'sender1' => 'TEST1-SG-2425-0100-0+000',
                'sender2' => 'TEST2-SG-2425-0100-0+000', // เพิ่มตามต้องการ
                default => null,
            };

            if (!$name_id) continue;

            $current = floatval($entry['current']);
            $status = $current > 0 ? 1 : 0;

            DB::table('routes')->updateOrInsert(
                ['name_id' => $name_id],
                [
                    'status' => $status,
                    'current' => $current,
                ]
            );
        }

        $this->info('Firebase data synced successfully.');
    }
}
