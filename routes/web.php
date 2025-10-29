<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

// Sucks that can't simply use 'redirect' because of HTMX.
// Htmx doesn't reload/redirect if status code isn't 2xx.
// @TODO: Maybe I don't have to do this? https://htmx.org/docs/#requests
function hx_redirect(string $path = "/") {
  return response('redirect')->header("HX-Redirect", $path);
}

Route::get("/", function () {
  if (Auth::check()) { return redirect("/home"); }
  else { return redirect("/login"); }
});

Route::get('/home', function () {
  return view('home');
})->middleware('auth');

Route::get('/register', function () {
  if (Auth::check()) { return redirect("/home"); }
  return view('register');
})->name("register");

Route::post('/register', function (Request $request) {
  if (User::where('email', $request->input("email"))->value("email")) {
    return response("User already exists with this email", 400);
  }

  $name = $request->input("name");
  if (User::where('name', $name)->value("name")) {
    return response("User with '$name' already exists", 400);
  }

  $info = $request->validate([
    "email" => "required|email|unique:users,email",
    "password" => "required|string",
    "name" => "required|string|unique:users,name",
    "nickname" => "required|string",
  ]);

  $user = User::create($info);
  Auth::login($user, true);
  return hx_redirect("/home");
});

Route::get('/login', function () {
  if (Auth::check()) { return redirect("/home"); }
  return view('login');
})->name("login");

Route::post('/login', function (Request $request) {
  $info = $request->validate([
    "email" => "required|email",
    "password" => "required|string",
  ]);

  if (Auth::attempt($info)) {
    $request->session()->regenerate();
    return hx_redirect("/home");
  }

  return response("User not found", 400);
});

Route::get("/logout", function (Request $request) {
  Auth::logout();
  $request->session()->invalidate();
  $request->session()->regenerateToken();
  return redirect('/');
})->middleware('auth');
