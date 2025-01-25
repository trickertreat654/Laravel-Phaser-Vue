<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Trash;
use App\Models\Score;
use Illuminate\Http\Request;

Route::get('/', function () {

    

    
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        
    ]);
});

Route::put('/trash', function (Request $request) {

    Score::create([
        'my_score' => $request->score,
        'name' => $request->name,
    ]);

    // $trash = Trash::first();
    // $trash->update(['name' => 'Trash'.rand(0, 100)]);
    return redirect()->back();
})->name('trash.update');

Route::get('/dashboard', function (Request $request) {

    if($request->spot == null){
        $number = 0;
    }
    else{
        $number = $request->spot['x'] + $request->spot['y'];
    }

    //$number = rand(0, 100);
    $scores = Score::all();
    return Inertia::render('Dashboard', [
        'scores' => $scores,
        'number' => $number,
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
