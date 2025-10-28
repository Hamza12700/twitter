<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('home');
})->middleware('auth');

Route::get('/register', function () {
  return view('register');
})->name("register");

Route::get('/login', function () {
  return view('login');
})->name("login");
