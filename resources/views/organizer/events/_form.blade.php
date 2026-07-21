@php $inputClass = 'w-full px-5 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-indigo-600 transition'; @endphp

<div>
    <label class="block text-sm font-bold text-slate-700 mb-2">Judul Event</label>
    <input type="text" name="title" value="{{ old('title', $event->title ?? '') }}" class="{{ $inputClass }}" required>
</div>

<div>
    <label class="block text-sm font-bold text-slate-700 mb-2">Kategori</label>
    <select name="category_id" class="{{ $inputClass }}" required>
        <option value="">-- Pilih Kategori --</option>
        @foreach($categories as $category)
        <option value="{{ $category->id }}" {{ (string) old('category_id', $event->category_id ?? '') === (string) $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
        @endforeach
    </select>
</div>

<div>
    <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi</label>
    <textarea name="description" rows="4" class="{{ $inputClass }}">{{ old('description', $event->description ?? '') }}</textarea>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal & Waktu</label>
        <input type="datetime-local" name="date" value="{{ old('date', isset($event->date) ? $event->date->format('Y-m-d\TH:i') : '') }}" class="{{ $inputClass }}" required>
    </div>
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Lokasi</label>
        <input type="text" name="location" value="{{ old('location', $event->location ?? '') }}" class="{{ $inputClass }}" required>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Harga Tiket (Rp)</label>
        <input type="number" name="price" value="{{ old('price', $event->price ?? '') }}" min="0" class="{{ $inputClass }}" required>
    </div>
    <div>
        <label class="block text-sm font-bold text-slate-700 mb-2">Stok Tiket</label>
        <input type="number" name="stock" value="{{ old('stock', $event->stock ?? '') }}" min="1" class="{{ $inputClass }}" required>
    </div>
</div>

<div>
    <label class="block text-sm font-bold text-slate-700 mb-2">Poster (opsional, maks 2MB)</label>
    @if(!empty($event->poster_path))
    <img src="{{ asset('storage/' . $event->poster_path) }}" class="w-32 h-32 object-cover rounded-2xl mb-3 border">
    @endif
    <input type="file" name="poster" accept="image/*" class="{{ $inputClass }} text-sm text-slate-600">
</div>

<label class="flex items-center gap-3 bg-slate-50 border-2 border-slate-100 rounded-2xl px-5 py-4 cursor-pointer">
    <input type="hidden" name="is_published" value="0">
    <input type="checkbox" name="is_published" value="1" class="w-5 h-5 rounded text-indigo-600 focus:ring-indigo-500" {{ old('is_published', $event->is_published ?? false) ? 'checked' : '' }}>
    <span class="text-sm font-bold text-slate-700">Publikasikan event ini ke katalog publik</span>
</label>