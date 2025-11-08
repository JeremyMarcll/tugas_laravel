@extends('layouts.app')

@section('title', $transaction->title)

@section('content')

<div class="card shadow-sm p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">{{ $transaction->title }}</h2>
        <a href="{{ route('transactions.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

```
<div class="row mb-3">
    <div class="col-md-6 mb-2">
        <strong>Tipe:</strong> 
        @if($transaction->type === 'income')
            <span class="badge bg-success">Pemasukan</span>
        @else
            <span class="badge bg-danger">Pengeluaran</span>
        @endif
    </div>
    <div class="col-md-6 mb-2">
        <strong>Jumlah:</strong> Rp {{ number_format($transaction->amount, 2, ',', '.') }}
    </div>
    <div class="col-md-6 mb-2">
        <strong>Kategori:</strong> {{ $transaction->category ?? '-' }}
    </div>
    <div class="col-md-6 mb-2">
        <strong>Tanggal:</strong> {{ optional($transaction->occurred_at)->format('d M Y') ?? '-' }}
    </div>
</div>

<div class="mb-3">
    <strong>Bukti:</strong><br>
    @if($transaction->bukti)
        @php $ext = pathinfo($transaction->bukti, PATHINFO_EXTENSION); @endphp
        @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp']))
            <a href="{{ Storage::url($transaction->bukti) }}" target="_blank">
                <img src="{{ Storage::url($transaction->bukti) }}" class="img-fluid rounded mt-2" style="max-height:300px;">
            </a>
        @else
            <a href="{{ Storage::url($transaction->bukti) }}" target="_blank" class="btn btn-outline-primary btn-sm mt-2">Lihat Bukti</a>
        @endif
    @else
        <small class="text-muted">Tidak ada bukti</small>
    @endif
</div>

@if($transaction->notes)
    <div class="mb-3">
        <strong>Catatan:</strong>
        <div class="border rounded p-2 mt-1 bg-light">{!! nl2br(e($transaction->notes)) !!}</div>
    </div>
@endif
```

</div>

@endsection
