@extends('layouts.app')

@section('title','Edit Transaksi')

@section('content')

<div class="card p-4 shadow-sm">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Edit Transaksi</h3>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>


<form action="{{ route('transactions.update', $transaction->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label>Judul</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $transaction->title) }}">
    </div>

    <div class="mb-3">
        <label>Type</label>
        <select name="type" class="form-select">
            <option value="income" {{ old('type', $transaction->type)=='income'?'selected':'' }}>Pemasukan</option>
            <option value="expense" {{ old('type', $transaction->type)=='expense'?'selected':'' }}>Pengeluaran</option>
        </select>
    </div>

    <div class="mb-3">
        <label>Jumlah</label>
        <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount', $transaction->amount) }}">
    </div>

    <div class="mb-3">
        <label>Kategori</label>
        <input name="category" class="form-control" value="{{ old('category', $transaction->category) }}">
    </div>

    <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" name="occurred_at" class="form-control" 
            value="{{ old('occurred_at', optional($transaction->occurred_at)->format('Y-m-d')) }}">
    </div>

    <div class="mb-3">
        <label>Catatan</label>
        <textarea name="notes" class="form-control">{{ old('notes', $transaction->notes) }}</textarea>
    </div>

    <div class="mb-3">
        <label>Bukti</label><br>
        @if($transaction->bukti)
            @php
                $ext = pathinfo($transaction->bukti, PATHINFO_EXTENSION);
            @endphp
            @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp']))
                <img src="{{ Storage::url($transaction->bukti) }}" style="max-height:120px" class="mb-2 rounded">
            @else
                <a href="{{ Storage::url($transaction->bukti) }}" target="_blank">Lihat Bukti</a>
            @endif
        @endif
        <input type="file" name="bukti" class="form-control" accept="image/*,application/pdf">
    </div>

    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
</form>


</div>

@endsection
