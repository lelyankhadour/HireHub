<?php

use App\Http\Controllers\Api\V1\ReviewController;
use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\{
    ProjectController,
    FreelancerController,
    BidController,
    ProfileController,
    DashboardController
};

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Auth Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
    });

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index']);

    /*
    |--------------------------------------------------------------------------
    | Projects Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('projects')->group(function () {

        Route::get('/', [ProjectController::class, 'index']);
        Route::get('/{project}', [ProjectController::class, 'show']);

        // Only clients can create projects
        Route::middleware(['auth:sanctum', 'client'])
            ->post('/', [ProjectController::class, 'store']);

        // Only verified freelancers can bid
        Route::middleware(['auth:sanctum', 'freelancer.verified'])
            ->post('/{project}/bids', [BidController::class, 'store']);

        // Close project (client only)
        Route::middleware(['auth:sanctum', 'client'])
            ->post('/{project}/close', [ProjectController::class, 'close']);
    });

    /*
    |--------------------------------------------------------------------------
    | Bids Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('bids')->middleware('auth:sanctum')->group(function () {
        Route::get('/{bid}', [BidController::class, 'show']);

        // Only client can accept a bid
        Route::middleware('client')
            ->post('/{id}/accept', [BidController::class, 'accept']);
    });

    /*
    |--------------------------------------------------------------------------
    | Freelancers Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('freelancers')->group(function () {
        Route::get('/', [FreelancerController::class, 'index']);
        Route::get('/{user}', [FreelancerController::class, 'show']);
    });

    /*
    |--------------------------------------------------------------------------
    | Profile Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('profile')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::put('/', [ProfileController::class, 'update']);

        // Update skills (freelancer only)
        Route::middleware(['freelancer.verified'])
            ->put('/skills', [ProfileController::class, 'updateProfileSkill']);
    });

    /*
    |--------------------------------------------------------------------------
    | Reviews Routes (Client Only)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth:sanctum', 'client'])->group(function () {

        // Review a project
        Route::post('/projects/{project}/reviews', [ReviewController::class, 'reviewProject']);

        // Review a freelancer
        Route::post('/freelancers/{user}/reviews', [ReviewController::class, 'reviewFreelancer']);
    });

});
