<?php

use App\Http\Controllers\PetController;
use Illuminate\Support\Facades\Route;

// Home and Main Pages
Route::get('/', [PetController::class, 'index'])->name('home');
Route::get('/home', [PetController::class, 'index']);

// Species-specific Pages
Route::get('/dog', [PetController::class, 'dogs'])->name('dog');
Route::get('/cat', [PetController::class, 'cats'])->name('cat');
Route::get('/pokemon', [PetController::class, 'pokemon'])->name('pokemon');

// View All Pets (Table View)
Route::get('/view-all-pets', [PetController::class, 'viewAll'])->name('view.all');

// CRUD Operations
Route::get('/add-pet', [PetController::class, 'create'])->name('pet.create');
Route::post('/add-pet', [PetController::class, 'store'])->name('pet.store');
Route::get('/edit-pet/{id}', [PetController::class, 'edit'])->name('pet.edit');
Route::post('/update-pet/{id}', [PetController::class, 'update'])->name('pet.update');
Route::get('/delete-pet/{id}', [PetController::class, 'destroy'])->name('pet.delete');

// Search
Route::get('/search', [PetController::class, 'search'])->name('pet.search');