@extends('layout')

@section('content')
<div class="card auth-card shadow p-4" style="width: 400px;">
    <h3 class="text-center mb-4">Register</h3>

    <form method="POST" action="/register">
        @csrf

        <div class="mb-3">
            <input type="text" name="name" class="form-control" placeholder="Full Name" required>
        </div>

        <div class="mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
        </div>

        <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>

        <button class="btn btn-primary w-100">Register</button>
    </form>

    <p class="text-center mt-3">
        Already have an account?
        <a href="/login">Login</a>
    </p>
</div>
@endsection
