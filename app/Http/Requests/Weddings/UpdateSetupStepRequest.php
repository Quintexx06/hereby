<?php

namespace App\Http\Requests\Weddings;

use App\Enums\Celebration;
use App\Enums\EventType;
use App\Enums\GuestEstimate;
use App\Enums\Locale;
use App\Enums\SetupStep;
use App\Enums\WeddingTheme;
use App\Models\Wedding;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validates one setup step. The rules follow the `{step}` in the URL.
 */
class UpdateSetupStepRequest extends FormRequest
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
        /** @var SetupStep $step */
        $step = $this->route('step');

        return match ($step) {
            SetupStep::Couple => [
                'partner_one' => ['required', 'string', 'max:80'],
                'partner_two' => ['required', 'string', 'max:80'],
            ],
            SetupStep::Date => [
                'wedding_date' => ['required', 'date', 'after:today'],
                'rsvp_deadline' => ['nullable', 'date', 'after_or_equal:today', 'before:wedding_date'],
            ],
            SetupStep::Venue => [
                'venue_undecided' => ['boolean'],
                'venue_name' => ['exclude_if:venue_undecided,true', 'required', 'string', 'max:120'],
                'venue_address' => ['exclude_if:venue_undecided,true', 'nullable', 'string', 'max:200'],
                'venue_postcode' => ['exclude_if:venue_undecided,true', 'nullable', 'regex:/^\d{4}$/'],
                'venue_town' => ['exclude_if:venue_undecided,true', 'nullable', 'string', 'max:80'],
                // Within Switzerland and Liechtenstein.
                'venue_lat' => ['exclude_if:venue_undecided,true', 'nullable', 'numeric', 'between:45.7,47.9'],
                'venue_lng' => ['exclude_if:venue_undecided,true', 'nullable', 'numeric', 'between:5.9,10.6'],
                'venue_reference' => ['exclude_if:venue_undecided,true', 'nullable', 'string', 'max:64'],
            ],
            SetupStep::Programme => [
                'celebration' => ['required', Rule::enum(Celebration::class)],
                'events' => ['required', 'array', 'min:1', 'max:8'],
                'events.*.type' => ['required', Rule::enum(EventType::class)],
                'events.*.time' => ['required', 'date_format:H:i'],
                'events.*.day_offset' => ['required', 'integer', 'between:-1,1'],
                'events.*.name' => ['nullable', 'string', 'max:80'],
            ],
            SetupStep::Guests => [
                'guest_estimate' => ['required', Rule::enum(GuestEstimate::class)],
                'languages' => ['required', 'array', 'min:1'],
                'languages.*' => ['distinct', Rule::enum(Locale::class)],
                'default_locale' => ['required', Rule::enum(Locale::class), Rule::in($this->input('languages', []))],
            ],
            SetupStep::Look => [
                'theme' => ['required', Rule::enum(WeddingTheme::class)],
            ],
            SetupStep::Review => [],
        };
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'partner_one.required' => 'Wie heisst ihr? Beide Namen bitte.',
            'partner_two.required' => 'Wie heisst ihr? Beide Namen bitte.',
            'wedding_date.after' => 'Das Datum muss in der Zukunft liegen.',
            'rsvp_deadline.before' => 'Die Antwortfrist muss vor der Hochzeit liegen.',
            'venue_name.required' => 'Wie heisst eure Location? Oder wählt «Noch offen».',
            'events.required' => 'Wählt mindestens einen Teil eures Tages.',
            'default_locale.in' => 'Die Hauptsprache muss eine der gewählten Sprachen sein.',
        ];
    }
}
