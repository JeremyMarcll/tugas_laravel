@extends('layouts.app')

@section('title','Login')

@section('content')
<div class="card">
    <h2>Login</h2>
    <form method="POST" action="{{ url('login') }}">
        @csrf
        <div>
            <label>Username</label><br>
            <input name="username" value="{{ old('username') }}" required>
            @error('username') <div style="color:red">{{ $message }}</div> @enderror
        </div>
        <div>
            <label>Password</label><br>
            <input name="password" type="password" required>
            @error('password') <div style="color:red">{{ $message }}</div> @enderror
        </div>
        <div style="margin-top:8px">
            <button type="submit">Login</button>
        </div>
    </form>
</div>
@endsection
