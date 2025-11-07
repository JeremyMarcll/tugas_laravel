@extends('layouts.app')

@section('title',$transaction->title)

@section('content')
<div class="card">
    <h2>{{ $transaction->title }}</h2>
    <div>Type: {{ $transaction->type }}</div>
    <div>Amount: {{ number_format($transaction->amount,2,',','.') }}</div>
    <div>Category: {{ $transaction->category }}</div>
    <div>Tanggal: {{ optional($transaction->occurred_at)->format('Y-m-d') }}</div>
    @if($transaction->cover_path)
        <div><img src="{{ Storage::url($transaction->cover_path) }}" style="max-width:100%"></div>
    @endif
    <div>{!! $transaction->notes !!}</div>
    <div style="margin-top:8px"><a href="{{ route('transactions.index') }}">Kembali</a></div>
</div>
@endsection
