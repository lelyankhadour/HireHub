<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileSkillUpdateRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected ProfileService $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    public function show(Request $request)
    {
        $user = $request->user()->load([
            'city',
            'skills',
            'reviews.reviewer',
        ]);

        return new ProfileResource($user);
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $request->user();

        $updated = $this->service->updateProfile($user, $request->validated());

        return new ProfileResource($updated);
    }

    public function updateProfileSkill(ProfileSkillUpdateRequest $request)
    {
        $user = auth()->user();

        abort_if($user->role !== 'freelancer', 403);

        $skills = $this->service->updateProfileSkill($user, $request->validated()['skills']);

        return response()->json([
            'message' => 'Skills updated successfully.',
            'skills'  => $skills,
        ]);
    }
}
