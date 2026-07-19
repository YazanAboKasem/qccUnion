<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PledgeRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PledgeController extends Controller
{
    /**
     * عرض قائمة التعهدات مع إمكانية البحث والفلترة.
     */
    public function index(Request $request)
    {
        $query = PledgeRecord::query()->latest('signed_at');

        // البحث بالاسم
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        // فلترة حسب تاريخ التوقيع
        if ($from = $request->input('date_from')) {
            $query->whereDate('signed_at', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->whereDate('signed_at', '<=', $to);
        }

        $pledges = $query->paginate(25)->withQueryString();
        $totalCount = PledgeRecord::count();

        return view('pledges.index', compact('pledges', 'totalCount'));
    }

    /**
     * عرض تفاصيل تعهد واحد.
     */
    public function show(PledgeRecord $pledge)
    {
        return view('pledges.show', compact('pledge'));
    }

    /**
     * حذف تعهد.
     */
    public function destroy(PledgeRecord $pledge)
    {
        // حذف ملف التوقيع إن وُجد
        if ($pledge->signature_path) {
            Storage::disk('public')->delete($pledge->signature_path);
        }

        $pledge->delete();

        return redirect()->route('pledges.index')
            ->with('success', 'تم حذف التعهد بنجاح.');
    }

    /**
     * تصدير التعهدات كملف CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = PledgeRecord::query()->latest('signed_at');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }
        if ($from = $request->input('date_from')) {
            $query->whereDate('signed_at', '>=', $from);
        }
        if ($to = $request->input('date_to')) {
            $query->whereDate('signed_at', '<=', $to);
        }

        $pledges = $query->get();

        $filename = 'pledges_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($pledges) {
            $handle = fopen('php://output', 'w');
            // BOM for Excel Arabic support
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, ['ID', 'Name', 'Version', 'Signed At', 'Device UUID', 'App Version', 'Synced At']);
            foreach ($pledges as $p) {
                fputcsv($handle, [
                    $p->id,
                    $p->name,
                    $p->pledge_text_version,
                    $p->signed_at?->format('Y-m-d H:i:s'),
                    $p->device_uuid,
                    $p->app_version,
                    $p->synced_at?->format('Y-m-d H:i:s'),
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=utf-8']);
    }
}
