@extends('layout')

@section('content')
<div class="card shadow p-5 text-center" style="width: 520px;">

    <h2 class="mb-2">
        Welcome {{ session('user_name') }} 🎉
    </h2>

    <p class="text-muted mb-3">
        Your session will expire in
        <strong class="text-danger">
            <span id="countdown"></span>
        </strong>
        seconds
    </p>

    <form id="logoutForm" method="POST" action="/logout">
        @csrf
        <button class="btn btn-danger mt-3">Logout</button>
    </form>

</div>
@endsection

@section('scripts')
<script>
    /* ===============================
       ⏱️ LIVE SESSION COUNTDOWN
    =============================== */
    let expiryTime = {{ session('session_expires_at') }} * 1000;

    let timer = setInterval(function () {
        let now = new Date().getTime();
        let diff = Math.floor((expiryTime - now) / 1000);

        if (diff <= 0) {
            clearInterval(timer);
            alert("⚠️ Your session has expired!");
            document.getElementById('logoutForm').submit();
        } else {
            document.getElementById("countdown").innerText = diff;
        }
    }, 1000);


    /* ===============================
       ❌ WINDOW / TAB CLOSE LOGOUT
    =============================== */
    window.addEventListener("beforeunload", function () {

        navigator.sendBeacon("/logout", new URLSearchParams({
            _token: "{{ csrf_token() }}"
        }));

    });
</script>
@endsection
