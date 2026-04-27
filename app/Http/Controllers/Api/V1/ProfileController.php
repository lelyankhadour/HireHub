<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileSkillUpdateRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Services\ProfileService;
use Illuminate\Http\Request;
use App\Traits\ResponseTrait;

class ProfileController extends Controller
{use ResponseTrait;
    protected ProfileService $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    public function show(Request $request)
    {try{
        $user = $request->user()->load([
            'city',
            'skills',
            'reviews.reviewer',
        ]);

        // return new ProfileResource($user);
         return $this->successResponse(new ProfileResource($user), "Operation completed successfully", 200);
            }catch (\Throwable $e) {
            return $this->errorResponse("Something went wrong " , 500);
        }
    }

    public function update(UpdateProfileRequest $request)
    {try{
        $user = $request->user();

        $updated = $this->service->updateProfile($user, $request->validated());

        // return new ProfileResource($updated);   
        return $this->successResponse(new ProfileResource($updated), "Operation completed successfully", 200);
         }catch (\Throwable $e) {
            return $this->errorResponse("Something went wrong ", 500);
        }
    }

    public function updateProfileSkill(ProfileSkillUpdateRequest $request)
    {try{
        $user = auth()->user();

        // abort_if($user->role !== 'freelancer', 403);
     

abort_if($user->role !== UserRole::Freelancer, 403);


        $skills = $this->service->updateProfileSkill($user, $request->validated()['skills']);

        // return response()->json([
        //     'message' => 'Skills updated successfully.',
        //     'skills'  => $skills,
        // ]);   
         return $this->successResponse($skills, "Operation completed successfully", 200);
         }catch (\Throwable $e) {
            return $this->errorResponse("Something went wrong " , 500);
        }
    }
}
