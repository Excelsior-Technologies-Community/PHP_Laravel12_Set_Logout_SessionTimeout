# PHP_Laravel12_Set_Logout_SessionTimeout

# Step 1: Install Laravel 12 and Create project for use command
```php
composer create-project laravel/laravel PHP_Laravel12_Set_Logout_SessionTimeout
```
# Now Open Folder for 
```php
cd PHP_Laravel12_Set_Logout_SessionTimeout
```
# Step 2: Setup DataBase For .env file
```php
 DB_CONNECTION=mysql
 DB_HOST=127.0.0.1
 DB_PORT=3306
 DB_DATABASE=your database name
 DB_USERNAME=root
 DB_PASSWORD=
```
# Step 3: Create Migration File For Database Table
```php
php artisan make:migration create_authentication_table
```
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('authentication', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authentication');
    }
};

```

# Run migration:
```php
php artisan migrate
```

# Step 4: Create Model
```php
php artisan make:model Authentication
```
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Authentication extends Model
{
  protected $table = 'authentication';
  protected $fillable = ['name','email','password'];
}
```
# Step 5: Create Contaroller 
```php
php artisan make:controller AuthController
```
```php
<?php

namespace App\Http\Controllers;

use App\Models\Authentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // ================= REGISTER =================
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:authentication',
            'password' => 'required|min:6'
        ]);

        Authentication::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return redirect('/login')->with('success', 'Registration successful');
    }

    // ================= LOGIN =================
public function login(Request $request)
{
    $user = Authentication::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return back()->with('error', 'Email or Password is incorrect');
    }

    $seconds = 35; //  session duration (changeable)

    Session::put('user_id', $user->id);
    Session::put('user_name', $user->name);

    //  expiry timestamp store
    Session::put('session_expires_at', time() + $seconds);

    return redirect('/dashboard');
}


    // ================= DASHBOARD =================
   public function dashboard()
{
    if (!Session::has('user_id')) {
        return redirect('/login');
    }

    if (time() > Session::get('session_expires_at')) {
        Session::flush();
        return redirect('/login')->with('session_expired', true);
    }

    return view('dashboard');
}

    // ================= LOGOUT =================
    public function logout()
    {
        Session::flush();
        return redirect('/login');
    }
}
```
# Step 6: Create Route For Web.php file

Routes/web.php
```php
<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Pages
Route::view('/register', 'register');
Route::view('/login', 'login');

// Actions
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/dashboard', [AuthController::class, 'dashboard']);
Route::post('/logout', [AuthController::class, 'logout']);
```
# Step 7: Create Blade file and layout file in resource/view folder
# resources/views/layout.blade.php
```php
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
```
# resources/views/register.blade.php
```php
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

```
# resources/views/login.blade.php 
```php
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
```

# resources/views/dashboard.blade.php 
```php
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
        LIVE SESSION COUNTDOWN
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
        WINDOW / TAB CLOSE LOGOUT
    =============================== */
    window.addEventListener("beforeunload", function () {

        navigator.sendBeacon("/logout", new URLSearchParams({
            _token: "{{ csrf_token() }}"
        }));

    });
</script>
@endsection
```
# Step 8: Now Run Server and paste this url
```php
php artisan serve
http://127.0.0.1:8000/register
```
<img width="1437" height="705" alt="image" src="https://github.com/user-attachments/assets/0583b9cd-69f9-4439-8c0d-8df4399a022b" />

# Now After Successful Registration and  redirect page login
<img width="1148" height="450" alt="image" src="https://github.com/user-attachments/assets/77a3bcde-6c03-4099-a212-621cc10545ba" />


 

# Now Complete successful login and open welcome dashboard page and your logout session timing starting and after second finished then automatically  your login id is logout and page redirect to login page :
 <img width="897" height="381" alt="image" src="https://github.com/user-attachments/assets/d7965cd4-aa6f-4f3b-8eb3-07f10ab44390" />
 <img width="1622" height="693" alt="image" src="https://github.com/user-attachments/assets/1337985d-cf94-4dd0-9f7e-8df1684a0610" />


 







