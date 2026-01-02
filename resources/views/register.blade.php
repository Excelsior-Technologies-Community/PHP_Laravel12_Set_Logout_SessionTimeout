@extends('layout')

@section('content')
<div class="card auth-card shadow p-4" style="width: 420px;">
    <h3 class="text-center mb-4">Register</h3>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/register">
        @csrf

        <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Full Name" value="{{ old('name') }}">
        </div>

        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password">
        </div>

        <button class="btn btn-primary w-100">Register</button>
    </form>

    <p class="text-center mt-3">
        Already registered?
        <a href="/login">Login</a>
    </p>
</div>
@endsection
