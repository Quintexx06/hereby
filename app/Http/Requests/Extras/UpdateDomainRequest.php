<?php

namespace App\Http\Requests\Extras;

use App\Enums\Addon;
use App\Models\Wedding;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * «Eigene Domain»: a bare host name, written however the couple pastes it
 * (with https://, a slash, capitals), used by no other wedding.
 */
class UpdateDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        $wedding = $this->route('wedding');

        return $wedding instanceof Wedding && $wedding->hasAddon(Addon::CustomDomain);
    }

    protected function prepareForValidation(): void
    {
        $domain = Str::of($this->string('domain')->value())->trim()->lower()
            ->replaceMatches('#^https?://#', '')
            ->before('/')
            ->value();

        $this->merge(['domain' => $domain === '' ? null : $domain]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Wedding $wedding */
        $wedding = $this->route('wedding');

        return [
            'domain' => [
                'nullable',
                'string',
                'max:191',
                'regex:/^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,}$/',
                Rule::unique('weddings', 'custom_domain')->ignore($wedding->id),
                // Hereby's own host, and anything under it, is never a couple's.
                'not_regex:/(^|\.)'.preg_quote($this->ownHost(), '/').'$/',
            ],
        ];
    }

    private function ownHost(): string
    {
        return Str::of((string) parse_url((string) config('app.url'), PHP_URL_HOST))->lower()->after('www.')->value();
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'domain.regex' => 'Bitte nur die Adresse, etwa anna-und-luca.ch.',
            'domain.unique' => 'Diese Domain ist schon vergeben.',
            'domain.not_regex' => 'Das ist unsere Adresse. Tragt eure eigene Domain ein, etwa anna-und-luca.ch.',
        ];
    }
}
