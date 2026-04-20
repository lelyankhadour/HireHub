<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CleanContent;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === 'client';
    }

    public function rules(): array
    {

        return [
            'title' => [
                'required',
                'string',
                'min:10',
                new CleanContent,
            ],

            'description' => [
                'required',
                'string',
                'min:20',
                new CleanContent,
            ],
            'budget_type' => ['required', 'in:fixed,hourly'],
            'budget_amount' => ['nullable', 'numeric', 'min:1'],
            'deadline' => ['nullable', 'date', 'after:today'],
            'tags' => ['nullable', 'array', 'max:5'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ];
    }
}
