<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CleanContent;

class UpdateBidRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->userIsOwner()
            && $this->projectIsOpen()
            && $this->bidIsPending();
    }

    private function bid()
    {
        return $this->route('bid');
    }

    private function project()
    {
        return $this->bid()->project;
    }

    private function userIsOwner(): bool
    {
        return auth()->id() === $this->bid()->freelancer_id;
    }

    private function projectIsOpen(): bool
    {
        return $this->project()->status === 'open';
    }

    private function bidIsPending(): bool
    {
        return $this->bid()->status === 'pending';
    }

    public function rules(): array
    {
        return [
            'amount' => ['sometimes', 'numeric', 'min:1'],
            'delivery_days' => ['sometimes', 'integer', 'min:1'],
            'cover_letter' => ['sometimes', 'string', 'min:20', new CleanContent],
        ];
    }
}
