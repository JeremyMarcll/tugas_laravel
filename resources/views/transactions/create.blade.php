@extends('layouts.app')

@section('title','Tambah Transaksi')

@section('content')
<div class="card p-4 shadow-sm">
    <h3 class="mb-3">Tambah Transaksi</h3>
    <form method="POST" action="{{ route('transactions.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Judul</label>
            <input name="title" class="form-control" value="{{ old('title') }}" required>
            @error('title') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label>Catatan</label>
            <input id="notes" type="hidden" name="notes" value="{{ old('notes') }}">
            <trix-editor input="notes"></trix-editor>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Tipe</label>
                <select name="type" class="form-select" required>
                    <option value="income" {{ old('type')=='income'?'selected':'' }}>Pemasukan</option>
                    <option value="expense" {{ old('type')=='expense'?'selected':'' }}>Pengeluaran</option>
                </select>
            </div>
            <div class="col-md-4">
                <label>Jumlah</label>
                <input name="amount" type="number" class="form-control" value="{{ old('amount') }}" required>
                @error('amount') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label>Tanggal</label>
                <input type="date" name="occurred_at" class="form-control" value="{{ old('occurred_at', date('Y-m-d')) }}">
            </div>
        </div>

        <div class="mb-3">
            <label>Kategori</label>
            <input name="category" class="form-control" value="{{ old('category') }}">
        </div>

        <div class="mb-3">
            <label>Cover (gambar)</label>
            <input type="file" name="cover" accept="image/*" class="form-control">
            @error('cover') <div class="text-danger small">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary ms-2">Batal</a>
    </form>
</div>
@endsection
