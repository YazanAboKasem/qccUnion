<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PledgeRecord;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the Union Pledge Dashboard metrics and recent activity.
     */
    public function index()
    {
        // 1. Total pledges
        $totalPledges = PledgeRecord::count();

        // 2. Today's pledges
        $todayPledges = PledgeRecord::whereDate('signed_at', now()->toDateString())->count();

        // 3. Unique devices/kiosks active
        $uniqueDevices = PledgeRecord::whereNotNull('device_uuid')
            ->distinct('device_uuid')
            ->count('device_uuid');

        // 4. Recent pledges (last 10)
        $recentPledges = PledgeRecord::orderBy('signed_at', 'desc')->take(10)->get();

        // 5. Hourly activity for today (DB-agnostic grouping in PHP to prevent SQLite/MySQL mismatch errors)
        $todayPledgesList = PledgeRecord::whereDate('signed_at', now()->toDateString())->get();
        $hourlyActivity = array_fill(0, 24, 0);
        foreach ($todayPledgesList as $record) {
            if ($record->signed_at) {
                $hour = intval($record->signed_at->format('H'));
                $hourlyActivity[$hour]++;
            }
        }

        return view('dashboard.index', compact(
            'totalPledges',
            'todayPledges',
            'uniqueDevices',
            'recentPledges',
            'hourlyActivity'
        ));
    }
}
