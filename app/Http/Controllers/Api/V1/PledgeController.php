<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePledgeRequest;
use App\Models\PledgeRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PledgeController extends Controller
{
    /**
     * استقبال تعهد واحد من التطبيق.
     * POST /api/v1/pledges
     */
    public function store(StorePledgeRequest $request): JsonResponse
    {
        $data = $request->validated();

        // منع التكرار إذا كان local_uuid موجوداً مسبقاً
        if (!empty($data['local_uuid'])) {
            $existing = PledgeRecord::where('local_uuid', $data['local_uuid'])->first();
            if ($existing) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم تسجيل التعهد مسبقاً.',
                    'data'    => ['id' => $existing->id],
                ], 200);
            }
        }

        // حفظ صورة التوقيع كملف PNG
        $signaturePath = $this->saveSignature($data['signature_base64'], $data['local_uuid'] ?? null);

        $pledge = PledgeRecord::create([
            'name'                => $data['name'],
            'pledge_text_version' => $data['pledge_text_version'] ?? 'v1',
            'signature_base64'    => $data['signature_base64'],
            'signature_path'      => $signaturePath,
            'signed_at'           => $data['signed_at'],
            'app_version'         => $data['app_version'] ?? null,
            'device_uuid'         => $data['device_uuid'] ?? null,
            'local_uuid'          => $data['local_uuid'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل التعهد بنجاح.',
            'data'    => ['id' => $pledge->id],
        ], 201);
    }

    /**
     * رفع دُفعة من التعهدات المحفوظة أوفلاين.
     * POST /api/v1/pledges/batch
     */
    public function batchSync(Request $request): JsonResponse
    {
        $request->validate([
            'pledges'   => ['required', 'array', 'min:1', 'max:500'],
            'pledges.*' => ['array'],
        ]);

        $pledges    = $request->input('pledges');
        $created    = 0;
        $duplicates = 0;
        $errors     = 0;

        foreach ($pledges as $pledgeData) {
            try {
                // تحقق بسيط
                if (empty($pledgeData['name']) || empty($pledgeData['signature_base64'])) {
                    $errors++;
                    continue;
                }

                // منع التكرار
                if (!empty($pledgeData['local_uuid'])) {
                    $existing = PledgeRecord::where('local_uuid', $pledgeData['local_uuid'])->first();
                    if ($existing) {
                        $duplicates++;
                        continue;
                    }
                }

                $signaturePath = $this->saveSignature(
                    $pledgeData['signature_base64'],
                    $pledgeData['local_uuid'] ?? null
                );

                PledgeRecord::create([
                    'name'                => $pledgeData['name'],
                    'pledge_text_version' => $pledgeData['pledge_text_version'] ?? 'v1',
                    'signature_base64'    => $pledgeData['signature_base64'],
                    'signature_path'      => $signaturePath,
                    'signed_at'           => $pledgeData['signed_at'] ?? now()->toIso8601String(),
                    'app_version'         => $pledgeData['app_version'] ?? null,
                    'device_uuid'         => $pledgeData['device_uuid'] ?? null,
                    'local_uuid'          => $pledgeData['local_uuid'] ?? null,
                ]);

                $created++;
            } catch (\Throwable $e) {
                $errors++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "تمت المزامنة. جديد: {$created}، مكرر: {$duplicates}، أخطاء: {$errors}.",
            'data'    => [
                'created'    => $created,
                'duplicates' => $duplicates,
                'errors'     => $errors,
            ],
        ], 200);
    }

    /**
     * حفظ التوقيع كصورة PNG في التخزين.
     */
    private function saveSignature(string $base64Data, ?string $localUuid): ?string
    {
        try {
            // إزالة رأس data URI إن وجد
            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64Data);
            $decoded   = base64_decode($imageData);

            if (!$decoded) {
                return null;
            }

            $filename = 'pledges/signatures/' . ($localUuid ?? Str::uuid()) . '.png';
            Storage::disk('public')->put($filename, $decoded);

            return $filename;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
