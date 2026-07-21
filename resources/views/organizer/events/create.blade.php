@extends('layouts.organizer')
@section('page_title', 'Buat Event Baru')
@section('page_subtitle', 'Isi detail acara yang akan Anda selenggarakan.')

@section('content')
<div class="max-w-3xl bg-white rounded-3xl border border-slate-100 shadow-sm p-8">
    <form action="{{ route('organizer.events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @include('organizer.events._form', ['event' => null])
        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('organizer.events.index') }}" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-2xl font-bold hover:bg-slate-200 transition">Batal</a>
            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-black hover:bg-indigo-700 transition">Simpan Event</button>
        </div>
    </form>
</div>
@endsection