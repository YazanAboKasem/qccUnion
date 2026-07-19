@extends('layouts.admin')

@section('title', 'Union Pledge Overview')
@section('header_title', 'Union Pledge Dashboard')

@section('content')
    <!-- Dashboard Info Banner -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-850 rounded-2xl shadow-md p-6 text-white mb-8 border border-slate-800 relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-red-600/15 via-transparent to-transparent"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tight text-white uppercase flex items-center">
                    <span class="inline-block w-3.5 h-3.5 rounded-full bg-[#D70321] mr-2.5 animate-pulse"></span>
                    يوم عهد الاتحاد — لوحة تحكم التعهدات
                </h2>
                <p class="text-sm text-slate-400 mt-1.5">شاشة متابعة تواقيع الموظفين على وثيقة عهد الاتحاد في الوقت الفعلي وتصدير التقارير الرسمية.</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <a href="{{ route('pledges.index') }}" class="px-5 py-2.5 bg-[#D70321] hover:bg-red-700 text-white rounded-xl text-sm font-bold shadow-lg shadow-red-600/20 transition cursor-pointer">
                    عرض جميع التعهدات
                </a>
                <a href="{{ route('pledges.export') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl text-sm font-bold transition cursor-pointer">
                    تصدير ملف CSV
                </a>
            </div>
        </div>
    </div>

    <!-- Metrics Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Pledges -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center space-x-4">
            <div class="p-3.5 rounded-xl bg-red-50 text-[#D70321]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">إجمالي الموظفين الموقعين / Total Signees</p>
                <h3 class="text-3xl font-black text-slate-900 mt-1">{{ number_format($totalPledges) }}</h3>
            </div>
        </div>

        <!-- Today's Pledges -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center space-x-4">
            <div class="p-3.5 rounded-xl bg-emerald-50 text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">الموقعين اليوم / Signed Today</p>
                <h3 class="text-3xl font-black text-slate-900 mt-1">{{ number_format($todayPledges) }}</h3>
            </div>
        </div>

        <!-- Active Kiosks -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center space-x-4">
            <div class="p-3.5 rounded-xl bg-blue-50 text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">الأجهزة النشطة / Active Kiosks</p>
                <h3 class="text-3xl font-black text-slate-900 mt-1">{{ number_format($uniqueDevices) }}</h3>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Pledges Table -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-800">أحدث التعهدات المكتملة / Recent Signees</h3>
                    <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-600 rounded-full">Real-time Feed</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 text-xs uppercase font-bold">
                                <th class="py-3 px-4">اسم الموظف / Employee</th>
                                <th class="py-3 px-4">توقيت التعهد / Signed At</th>
                                <th class="py-3 px-4 text-center">التوقيع / Signature</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentPledges as $pledge)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="py-4 px-4">
                                        <div class="font-bold text-slate-800 text-sm">{{ $pledge->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $pledge->device_uuid ?? 'Kiosk Mode' }}</div>
                                    </td>
                                    <td class="py-4 px-4 text-slate-600 text-sm">
                                        {{ $pledge->signed_at ? $pledge->signed_at->timezone('Asia/Dubai')->format('Y-m-d h:i A') : 'N/A' }}
                                    </td>
                                    <td class="py-4 px-4 flex justify-center">
                                        @if($pledge->signature_path)
                                            <div class="bg-slate-50 hover:bg-[#F3EDD2]/50 p-2 rounded-lg transition duration-150">
                                                <img src="{{ Storage::url($pledge->signature_path) }}" 
                                                     alt="Signature" 
                                                     class="h-10 object-contain w-auto block filter hover:brightness-90"
                                                     style="background-color: transparent;">
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400">No Signature</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-8 text-center text-slate-400 text-sm">
                                        لا توجد تعهدات مسجلة حتى الآن.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Activity Chart Card -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-6">نشاط التوقيعات اليوم / Today's Hourly Activity</h3>
                
                <div class="relative w-full" style="height: 260px;">
                    <canvas id="hourlyActivityChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Configuration Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('hourlyActivityChart').getContext('2d');
            const hourlyData = @json(array_values($hourlyActivity));
            const hoursLabels = Array.from({length: 24}, (_, i) => `${i}:00`);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: hoursLabels,
                    datasets: [{
                        label: 'Pledges Signed',
                        data: hourlyData,
                        borderColor: '#D70321',
                        backgroundColor: 'rgba(215, 3, 33, 0.05)',
                        borderWidth: 2.5,
                        tension: 0.35,
                        fill: true,
                        pointBackgroundColor: '#D70321',
                        pointBorderColor: '#fff',
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                color: '#94a3b8'
                            },
                            grid: {
                                borderDash: [4, 4],
                                color: '#f1f5f9'
                            }
                        },
                        x: {
                            ticks: {
                                color: '#94a3b8',
                                maxTicksLimit: 8
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
