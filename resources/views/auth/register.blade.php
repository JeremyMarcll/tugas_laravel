@extends('layouts.app')

@section('title','Register')

@section('content')

<div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-sm p-4" style="width: 350px;">


    <!-- Logo Aplikasi -->
    <div class="text-center mb-4">
        <img src="{{ asset('logo.png') }}" alt="Logo Aplikasi" style="max-width: 100px;">
        <h4 class="mt-2">Catatan Keuangan</h4>
    </div>

    <!-- Form Register -->
    <form method="POST" action="{{ url('register') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                   value="{{ old('name') }}" required autofocus>
            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" 
                   value="{{ old('username') }}" required>
            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Email (opsional)</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                   value="{{ old('email') }}">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Daftar</button>
    </form>

    <div class="mt-3 text-center">
        Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a>
    </div>
</div>


</div>

@endsection
