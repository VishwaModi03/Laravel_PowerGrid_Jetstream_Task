<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Users\CreateUser;
use App\Livewire\Users\EditUser;
use App\Livewire\Roles\CreateRole;
use App\Livewire\Roles\EditRole;

Route::get('/', function () {
    return view('welcome');
});



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/users', function () {
        return view('users.index');
    })->name('users.index');

    Route::get('/users/create',CreateUser::class)->name('users.create');
    Route::get('/users/{user}/edit',EditUser::class)->name('users.edit');

    Route::get('/collection-users', function () {
        return view('collection-users');
    })->name('collection-users');

    Route::get('/roles',function(){
        return view('roles.index');
    })->name('roles.index');

    Route::get('/roles/create', CreateRole::class)->name('roles.create');
    Route::get('/roles/{role}/edit', EditRole::class)->name('roles.edit');
  
});

