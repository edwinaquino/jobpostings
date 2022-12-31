<?php

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// CONTROLLERS NAMING CONVETIONS:
// 7 Common Resource Routes:
// index - Show all listings
// show - SHOW single listing
// create - Show form to create new listing (POST)
// edit - show form to edit listing
// update - update listing (POST)
// destroy - Delete Listing

// // SHOW All Listings
Route::get('/', [ListingController::class, 'index']);
// '/' is the route
// 'ListingController' is the class from file: use App\Http\Controllers\ListingController.php
// 'index' is the method in 'ListingController' class

// CREATE Listing
Route::get('/listings/manage', [ListingController::class, 'manage'])->middleware('auth');

// Show Create Form
Route::get('/listings/create', [ListingController::class, 'create'])->middleware('auth');

// STORE LISTING
Route::post('/listings/store', [ListingController::class, 'store']);

// SHOW EDIT FORM
Route::get('/listings/{listing}/edit', [ListingController::class, 'edit'])->middleware('auth');
// UPDATE LISTING
Route::put('/listings/{listing}', [ListingController::class, 'update'])->middleware('auth');

// manage listings
Route::get('/listings/manage', [ListingController::class, 'manage'])->middleware('auth');

// DELETE LISTING
Route::delete('/listings/{listing}', [ListingController::class, 'destroy'])->middleware('auth');



// uSERS
Route::get('/register', [UserController::class, 'create'])->middleware('guest');
Route::post('/users', [UserController::class, 'store']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');;

Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');;
Route::post('/users/authenticate', [UserController::class, 'authenticate'])->middleware('guest');;





// // All Listings
// Route::get('/', function () {
//     return view('listings', [
//         'listings' => Listing::all() // getting data by using the App\Models\Listing; and the all() moethod

//     ]);
// });

// Single Listing
// Route::get('/listings/{id}', function ($id) {
//     return view('listing',[
//         'listing' => Listing::find($id)

//     ]);
// });

// gettingd the data from App\Models\Listing; and assigning a variable called $listing) and passing a variable called listing to the listing.blade.php file
// SHOW Listings
Route::get('/listings/{listing}', [ListingController::class, 'show']);










