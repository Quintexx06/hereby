<?php

namespace App\Http\Requests\Partners;

use App\Enums\PartnerType;
use App\Models\Wedding;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * The couple invites a venue, photographer or planner by mail.
 */
class StorePartnerInviteRequest extends FormRequest
{
    public function authorize(): bool
    {
        $wedding = $this->route('wedding');

        return $wedding instanceof Wedding && $this->user()?->can('update', $wedding) === true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => ['required', Rule::enum(PartnerType::class)],
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:191'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Wie heisst euer Partner?',
            'email.required' => 'An welche Adresse geht die Einladung?',
            'email.email' => 'Diese Adresse sieht nicht nach einer E-Mail aus.',
        ];
    }
}
