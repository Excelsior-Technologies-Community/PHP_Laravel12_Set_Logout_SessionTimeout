<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laravel Auth</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!--  Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!--  Custom Style -->
    <style>
        body {
            background: linear-gradient(120deg, #6f86d6, #48c6ef);
            min-height: 100vh;
        }
        .auth-card {
            border-radius: 12px;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    @yield('content')
</div>

<!--  Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@yield('scripts')

</body>
</html>
