@extends('layouts.admin')

@section('title', 'Pledge Records')
@section('header_title', 'Pledge Day – التعهدات')

@section('content')
<div class="space-y-6">

    {{-- Stats Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
            <div>
                <div class="text-2xl font-bold text-slate-800">{{ number_format($totalCount) }}</div>
                <div class="text-sm text-slate-500">Total Pledges / إجمالي التعهدات</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <div class="text-2xl font-bold text-slate-800">{{ \App\Models\PledgeRecord::whereDate('signed_at', today())->count() }}</div>
                <div class="text-sm text-slate-500">Today / اليوم</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
            </div>
            <div>
                <div class="text-2xl font-bold text-slate-800">{{ \App\Models\PledgeRecord::whereDate('signed_at', today())->count() }}</div>
                <div class="text-sm text-slate-500">Pending Sync / في الانتظار</div>
            </div>
        </div>
    </div>

    {{-- Search & Filters --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form method="GET" action="{{ route('pledges.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-wider">Search Name / البحث</label>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by name..."
                    class="w-full rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-wider">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 mb-1 uppercase tracking-wider">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                    class="rounded-lg border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400">
            </div>
            <button type="submit"
                class="px-5 py-2.5 bg-slate-800 text-white text-sm font-semibold rounded-lg hover:bg-slate-700 transition">
                Filter
            </button>
            @if(request()->hasAny(['search', 'date_from', 'date_to']))
                <a href="{{ route('pledges.index') }}"
                    class="px-5 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-lg hover:bg-slate-200 transition">
                    Clear
                </a>
            @endif
            <div class="ml-auto">
                <a href="{{ route('pledges.export', request()->only(['search', 'date_from', 'date_to'])) }}"
                    class="flex items-center space-x-2 px-5 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Export CSV</span>
                </a>
            </div>
        </form>
    </div>

    {{-- Pledges Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-bold text-slate-800">Pledge Records</h2>
            <span class="text-sm text-slate-500">{{ $pledges->total() }} records</span>
        </div>

        @if($pledges->isEmpty())
            <div class="py-16 text-center text-slate-400">
                <svg class="w-12 h-12 mx-auto mb-4 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                <p class="font-medium">No pledges found</p>
                <p class="text-sm mt-1">Pledges will appear here once submitted from the kiosk app.</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3 text-left font-semibold text-slate-500 uppercase tracking-wider text-xs">#</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-500 uppercase tracking-wider text-xs">Name / الاسم</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-500 uppercase tracking-wider text-xs">Signed At</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-500 uppercase tracking-wider text-xs">Device</th>
                        <th class="px-6 py-3 text-left font-semibold text-slate-500 uppercase tracking-wider text-xs">Signature</th>
                        <th class="px-6 py-3 text-right font-semibold text-slate-500 uppercase tracking-wider text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($pledges as $pledge)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 text-slate-400 font-mono text-xs">{{ $pledge->id }}</td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-800">{{ $pledge->name }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">v{{ $pledge->pledge_text_version }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            <div>{{ $pledge->signed_at?->format('d M Y') }}</div>
                            <div class="text-xs text-slate-400">{{ $pledge->signed_at?->format('H:i:s') }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-xs font-mono">
                            {{ Str::limit($pledge->device_uuid, 12, '...') ?? '—' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($pledge->signature_path)
                                <img src="{{ Storage::url($pledge->signature_path) }}"
                                    alt="Signature"
                                    class="h-16 w-36 object-contain">
                            @else
                                <span class="text-slate-300 text-xs">No file</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('pledges.show', $pledge) }}"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition mr-2">
                                View
                            </a>
                            <form action="{{ route('pledges.destroy', $pledge) }}" method="POST" class="inline"
                                onsubmit="return confirm('Delete this pledge? This cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-semibold text-rose-600 bg-rose-50 rounded-lg hover:bg-rose-100 transition">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $pledges->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
