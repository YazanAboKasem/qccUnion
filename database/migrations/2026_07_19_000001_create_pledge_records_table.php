<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pledge_records', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // اسم الموظف
            $table->string('pledge_text_version')->default('v1'); // رقم نسخة نص التعهد
            $table->text('signature_base64');                // صورة التوقيع بصيغة base64
            $table->string('signature_path')->nullable();    // مسار الملف المحفوظ
            $table->timestamp('signed_at');                  // وقت التوقيع (من الجهاز)
            $table->string('app_version')->nullable();       // إصدار التطبيق
            $table->string('device_uuid')->nullable();       // معرف الجهاز
            $table->string('local_uuid')->unique()->nullable(); // معرف محلي (لمنع التكرار)
            $table->timestamp('synced_at')->useCurrent();    // وقت الاستلام
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pledge_records');
    }
};
