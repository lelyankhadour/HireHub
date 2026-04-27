<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CleanContent;
use Illuminate\Validation\Rules\Enum;
use App\Enums\UserRole;
use App\Enums\BudgetType;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role === UserRole::Client;
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

            'budget_type' => ['required', new Enum(BudgetType::class)],
            'budget_amount' => ['nullable', 'numeric', 'min:1'],

            'deadline' => ['nullable', 'date', 'after:today'],

            'tags' => ['nullable', 'array', 'max:5'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ];
    }
}
