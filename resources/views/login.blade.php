@extends('layout')

@section('content')
<div class="card auth-card shadow p-4" style="width: 400px;">
    <h3 class="text-center mb-4">Login</h3>

    <form method="POST" action="/login">
        @csrf

        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
        </div>

        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
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
