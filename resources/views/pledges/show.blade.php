@extends('layouts.admin')

@section('title', 'Pledge – ' . $pledge->name)
@section('header_title', 'Pledge Detail / تفاصيل التعهد')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <a href="{{ route('pledges.index') }}"
            class="flex items-center space-x-2 text-sm text-slate-500 hover:text-slate-800 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Back to Pledges</span>
        </a>

        <form action="{{ route('pledges.destroy', $pledge) }}" method="POST"
            onsubmit="return confirm('Delete this pledge?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="flex items-center space-x-2 px-4 py-2 bg-rose-50 text-rose-600 text-sm font-semibold rounded-lg hover:bg-rose-100 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                <span>Delete</span>
            </button>
        </form>
    </div>

    {{-- Main Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        {{-- Header --}}
        <div class="px-8 py-6 bg-gradient-to-r from-slate-800 to-slate-700 text-white">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-full bg-white/10 flex items-center justify-center text-2xl font-bold">
                    {{ mb_substr($pledge->name, 0, 1) }}
                </div>
                <div>
                    <h1 class="text-xl font-bold">{{ $pledge->name }}</h1>
                    <p class="text-slate-300 text-sm mt-0.5">Pledge Version: v{{ $pledge->pledge_text_version }}</p>
                </div>
                <div class="ml-auto text-right">
                    <div class="text-sm text-slate-300">Pledge ID</div>
                    <div class="font-mono font-bold text-lg">#{{ $pledge->id }}</div>
                </div>
            </div>
        </div>

        {{-- Details Grid --}}
        <div class="px-8 py-6 grid grid-cols-2 gap-6">
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Signed At / وقت التوقيع</div>
                <div class="text-slate-800 font-semibold">{{ $pledge->signed_at?->format('d F Y – H:i:s') }}</div>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Received At / وقت الاستلام</div>
                <div class="text-slate-800 font-semibold">{{ $pledge->synced_at?->format('d F Y – H:i:s') ?? '—' }}</div>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Device UUID</div>
                <div class="text-slate-600 font-mono text-sm">{{ $pledge->device_uuid ?? '—' }}</div>
            </div>
            <div>
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">App Version</div>
                <div class="text-slate-600">{{ $pledge->app_version ?? '—' }}</div>
            </div>
            <div class="col-span-2">
                <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Local UUID</div>
                <div class="text-slate-500 font-mono text-xs break-all">{{ $pledge->local_uuid ?? '—' }}</div>
            </div>
        </div>

        <div class="border-t border-slate-100"></div>

        {{-- Signature --}}
        <div class="px-8 py-6">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">
                Signature / التوقيع
            </div>
            @if($pledge->signature_path)
                <div class="inline-block">
                    <img src="{{ Storage::url($pledge->signature_path) }}"
                        alt="Signature of {{ $pledge->name }}"
                        class="max-h-80 max-w-lg object-contain">
                </div>
                <div class="mt-2">
                    <a href="{{ Storage::url($pledge->signature_path) }}"
                        target="_blank"
                        class="text-xs text-blue-600 hover:underline">
                        Open full image ↗
                    </a>
                </div>
            @else
                <div class="flex items-center space-x-2 text-slate-400 text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Signature image file not available.</span>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
