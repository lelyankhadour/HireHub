<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\UserRole;

class ProfileSkillUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        // only freelancer can update their skills
        return auth()->check() && auth()->user()->role === UserRole::Freelancer;
    }

    public function rules(): array
    {
        return [
            'skills' => ['required', 'array', 'min:1'],

            'skills.*.id' => ['required', 'integer'],
            'skills.*.years_of_experience' => ['required', 'integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('skills')) {
            $cleaned = [];

            foreach ($this->skills as $skill) {
                $cleaned[] = [
                    'id' => isset($skill['id']) ? (int) $skill['id'] : null,
                    'years_of_experience' => isset($skill['years_of_experience'])
                        ? (int) $skill['years_of_experience']
                        : null,
                ];
            }

            $this->merge(['skills' => $cleaned]);
        }
    }
}
