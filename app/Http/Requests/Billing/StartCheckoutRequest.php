<?php

namespace App\Http\Requests\Billing;

use App\Enums\Plan;
use App\Models\Wedding;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * A plan bought online: Signature or Atelier, and only ever an upgrade.
 */
class StartCheckoutRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Wedding $wedding */
        $wedding = $this->route('wedding');

        return [
            'plan' => [
                'required',
                Rule::enum(Plan::class)->only([Plan::Signature, Plan::Atelier]),
                function (string $attribute, mixed $value, Closure $fail) use ($wedding): void {
                    $plan = Plan::tryFrom((string) $value);

                    if ($plan !== null && $wedding->amountFor($plan) <= 0) {
                        $fail('Dieses Paket habt ihr schon.');
                    }
                },
            ],
        ];
    }

    public function plan(): Plan
    {
        return Plan::from($this->string('plan')->toString());
    }
}
