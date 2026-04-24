<?php

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


    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
    });


    Route::get('/dashboard', [DashboardController::class, 'index']);


    Route::prefix('projects')->group(function () {

        Route::get('/', [ProjectController::class, 'index']);
        Route::get('/{project}', [ProjectController::class, 'show']);

        Route::middleware(['auth:sanctum'])
            ->post('/', [ProjectController::class, 'store']);


        Route::middleware(['auth:sanctum', 'freelancer.verified'])
            ->post('/{project}/bids', [BidController::class, 'store']);


    });


    Route::prefix('bids')->middleware('auth:sanctum')->group(function () {
        Route::get('/{bid}', [BidController::class, 'show']);
        Route::post('/{id}/accept', [BidController::class, 'accept'])->middleware('auth:sanctum');
    });

    Route::prefix('freelancers')->group(function () {
        Route::get('/', [FreelancerController::class, 'index']);
        Route::get('/{user}', [FreelancerController::class, 'show']);
    });


    Route::prefix('profile')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [ProfileController::class, 'show']);
        Route::put('/', [ProfileController::class, 'update']);

        // Route::middleware(['freelancer.verified'])
        //     ->put('/skills', [ProfileSkillController::class, 'update']);
        Route::middleware(['freelancer.verified'])
            ->put('/skills', [ProfileController::class, 'updateProfileSkill']);
    });


});
