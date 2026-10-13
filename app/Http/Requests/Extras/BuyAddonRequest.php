<?php

namespace App\Http\Requests\Extras;

use App\Enums\Addon;
use App\Models\Wedding;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * An extra bought online: only with a paid plan that allows it, and only
 * once unless it is bought per year.
 */
class BuyAddonRequest extends FormRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Wedding $wedding */
        $wedding = $this->route('wedding');

        return [
            'addon' => [
                'required',
                Rule::enum(Addon::class),
                function (string $attribute, mixed $value, Closure $fail) use ($wedding): void {
                    $addon = Addon::tryFrom((string) $value);

                    if ($addon !== null && ! $wedding->canBuy($addon)) {
                        $fail($wedding->plan === null
                            ? 'Zuerst braucht ihr ein Paket.'
                            : 'Dieses Extra habt ihr schon, oder euer Paket umfasst es nicht.');
                    }
                },
            ],
        ];
    }

    public function addon(): Addon
    {
        return Addon::from($this->string('addon')->toString());
    }
}
