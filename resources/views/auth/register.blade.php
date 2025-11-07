@extends('layouts.app')

@section('title','Register')

@section('content')
<div class="card">
    <h2>Daftar</h2>
    <form method="POST" action="{{ url('register') }}">
        @csrf
        <div>
            <label>Nama</label><br>
            <input name="name" value="{{ old('name') }}" required>
            @error('name') <div style="color:red">{{ $message }}</div> @enderror
        </div>
        <div>
            <label>Username</label><br>
            <input name="username" value="{{ old('username') }}" required>
            @error('username') <div style="color:red">{{ $message }}</div> @enderror
        </div>
        <div>
            <label>Email (opsional)</label><br>
            <input name="email" value="{{ old('email') }}">
            @error('email') <div style="color:red">{{ $message }}</div> @enderror
        </div>
        <div>
            <label>Password</label><br>
            <input name="password" type="password" required>
        </div>
        <div>
            <label>Konfirmasi Password</label><br>
            <input name="password_confirmation" type="password" required>
        </div>
        <div style="margin-top:8px">
            <button type="submit">Daftar</button>
        </div>
    </form>
</div>
@endsection
