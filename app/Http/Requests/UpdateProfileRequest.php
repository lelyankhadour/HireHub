<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CleanContent;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'string', 'min:2'],
            'last_name' => ['sometimes', 'string', 'min:2'],
            // unchangable if  we want to login in other role should use another email
            'email' => ['prohibited'],
            'type' => ['prohibited'],

            'phone' => ['sometimes', 'string', 'min:8'],
            'city_id' => ['sometimes', 'integer'],

            'bio' => ['sometimes', 'string', 'min:10', new CleanContent],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('bio')) {
            $this->merge([
                'bio' => trim($this->bio),
            ]);
        }

        if ($this->has('phone')) {
            $this->merge([
                'phone' => trim($this->phone),
            ]);
        }
    }
}
