<?php

namespace App\Http\Requests;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CleanContent;
use App\Enums\UserRole;
use App\Enums\ProjectStatus;

class StoreBidRequest extends FormRequest
{
    protected ?Project $cachedProject = null;

    public function authorize(): bool
    {
        return $this->userIsFreelancer()
            && $this->projectExists()
            && $this->notProjectOwner()
            && $this->projectIsOpen()
            && $this->notAlreadyBid();
    }

    private function project(): ?Project
    {
        if (!$this->cachedProject) {
            $projectId = $this->route('project');
            $this->cachedProject = Project::find($projectId);
        }

        return $this->cachedProject;
    }

    private function userIsFreelancer(): bool
    {
        return auth()->check() && auth()->user()->role === UserRole::Freelancer;
    }

    private function projectExists(): bool
    {
        return $this->project() !== null;
    }

    private function notProjectOwner(): bool
    {
        return optional($this->project())->client_id !== auth()->id();
    }

    private function projectIsOpen(): bool
    {
        return optional($this->project())->status === ProjectStatus::Open;
    }

    private function notAlreadyBid(): bool
    {
        return ! $this->project()
            ->bids()
            ->where('freelancer_id', auth()->id())
            ->exists();
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:1'],
            'delivery_days' => ['required', 'integer', 'min:1'],
            'cover_letter' => ['required', 'string', 'min:20', new CleanContent],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'amount' => $this->amount ? (float) $this->amount : null,
            'delivery_days' => $this->delivery_days ? (int) $this->delivery_days : null,
            'cover_letter' => $this->cover_letter ? trim($this->cover_letter) : null,
        ]);
    }
}
