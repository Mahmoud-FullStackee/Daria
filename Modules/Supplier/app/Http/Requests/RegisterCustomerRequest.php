<?php

namespace Modules\Supplier\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterSupplierRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'username'  => ['required', 'string', 'min:8', 'max:100', Rule::unique('suppliers', 'username')->withoutTrashed()->ignore($this->supplier)],
            'password'  => ['required', 'confirmed', Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()],

            'email'     => ['required', 'email', Rule::unique('suppliers', 'email')->withoutTrashed()->ignore($this->supplier)],
            'company_name'  => ['required', 'string', 'min:8', 'max:100', Rule::unique('suppliers', 'company_name')->withoutTrashed()->ignore($this->supplier)],
            'remarks'       => ['string', 'nullable'],

            'locations'     => ['sometimes', 'array', 'present'],
            'locations.*.country' => ['string', 'nullable'],
            'locations.*.city' => ['string', 'nullable'],
            'locations.*.state' => ['string', 'nullable'],
            'locations.*.zip' => ['string', 'nullable'],
            'locations.*.first_address' => ['string', 'nullable'],
            'locations.*.second_address' => ['string', 'nullable'],

            'contacts' => ['sometimes', 'array', 'present'],
            'contacts.*.email' => ['sometimes', 'nullable', 'email', Rule::unique('contacts', 'email')->where('contactable_type', 'Supplier')->withoutTrashed()->ignore($this->id, 'contactable_id')],
            'contacts.*.phone' => ['sometimes', 'nullable', 'string'],
            'contacts.*.mobile' => ['sometimes', 'nullable', 'string'],

            'logo' => ['sometimes', 'array', 'nullable'],
        ];
    }

    public function authorize()
    {
        return true;
    }
}
