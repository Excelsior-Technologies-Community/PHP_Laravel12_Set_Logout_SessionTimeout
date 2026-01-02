@extends('layout')

@section('content')
<div class="card auth-card shadow p-4" style="width: 420px;">
    <h3 class="text-center mb-4">Login</h3>

    {{-- Success --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Custom Error --}}
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

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

    <form method="POST" action="/login">
        @csrf

        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email Address" value="{{ old('email') }}">
        </div>

        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password">
        </div>

        <button class="btn btn-success w-100">Login</button>
    </form>

    <p class="text-center mt-3">
        Don’t have an account?
        <a href="/register">Register</a>
    </p>
</div>
@endsection

@section('scripts')
@if(session('session_expired'))
<script>
    alert("⚠️ Your session has expired. Please login again.");
</script>
@endif
@endsection
