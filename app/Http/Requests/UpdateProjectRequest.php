<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CleanContent;

class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->userIsOwner()
            && $this->projectIsOpen();
    }

    private function project()
    {
        return $this->route('project');
    }

    private function userIsOwner(): bool
    {
        return auth()->id() === $this->project()->client_id;
    }

    private function projectIsOpen(): bool
    {
        return $this->project()->status === 'open';
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'min:10', new CleanContent],
            'description' => ['sometimes', 'string', 'min:20', new CleanContent],

            'budget_type' => ['prohibited'],

            'budget_amount' => ['sometimes', 'numeric', 'min:1'],

            'deadline' => ['sometimes', 'date', 'after:today'],

            'tags' => ['sometimes', 'array', 'max:5'],
            'tags.*' => ['string'],
        ];
    }
}
