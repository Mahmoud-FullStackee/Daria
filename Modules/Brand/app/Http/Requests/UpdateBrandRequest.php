<?php

namespace Modules\Brand\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateBrandRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'name'         => ['required', 'string', 'min:3', 'max:100', Rule::unique('brands', 'name')->whereNull('deleted_at')->ignore($this->brand)],
            'is_active'    => ['nullable', 'boolean'],
            'remarks'      => ['string', 'nullable', 'max:255'],
            'logo'         => ['sometimes', 'array', 'nullable'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('edit-user');
    }

    protected function prepareForValidation()
    {
        if ($this->password == null) {
            $this->request->remove('password');
            $this->request->remove('password_confirmation');
            // dd($this->request);
        }
    }
}
