@extends('layouts.app')

@section('title','Edit Transaksi')

@section('content')
<div class="card p-4 shadow-sm">
    <h3 class="mb-3">Edit Transaksi</h3>
    <form method="POST" action="{{ route('transactions.update', $transaction) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Judul</label>
            <input name="title" class="form-control" value="{{ old('title', $transaction->title) }}" required>
        </div>

        <div class="mb-3">
            <label>Jenis</label>
            <select name="type" class="form-select">
                <option value="income" {{ old('type',$transaction->type)=='income'?'selected':'' }}>Pemasukan</option>
                <option value="expense" {{ old('type',$transaction->type)=='expense'?'selected':'' }}>Pengeluaran</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Jumlah</label>
            <input name="amount" class="form-control" type="number" value="{{ old('amount', $transaction->amount) }}">
        </div>

        <div class="mb-3">
            <label>Kategori</label>
            <input name="category" class="form-control" value="{{ old('category', $transaction->category) }}">
        </div>

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="occurred_at" class="form-control" value="{{ old('occurred_at', $transaction->occurred_at->format('Y-m-d')) }}">
        </div>

        <div class="mb-3">
            <label>Catatan</label>
            <textarea name="notes" class="form-control">{{ old('notes', $transaction->notes) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Cover</label><br>
            @if($transaction->cover_path)
                <img src="{{ Storage::url($transaction->cover_path) }}" style="max-height:120px" class="mb-2 rounded">
            @endif
            <input type="file" name="cover" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary ms-2">Batal</a>
    </form>
</div>
@endsection
