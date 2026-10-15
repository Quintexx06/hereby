<?php

namespace App\Http\Requests\Admin;

use App\Enums\PartnerType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * A new venue, photographer or planner, opened by the team.
 */
class StorePartnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(PartnerType::class)],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:191', 'unique:users,email'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Wie heisst der Partner?',
            'email.unique' => 'Mit dieser Adresse gibt es schon ein Konto.',
        ];
    }
}
