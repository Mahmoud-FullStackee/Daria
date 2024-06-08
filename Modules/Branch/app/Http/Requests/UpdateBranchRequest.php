<?php

namespace Modules\Branch\Http\Requests;

use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateBranchRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'name'     => ['required', 'string', 'min:3', 'max:100', Rule::unique('branches', 'name')->whereNull('deleted_at')->where('company_id', $this->company_id)->ignore($this->branch)],
            'is_main'  => ['required', 'boolean'],
            'is_active'  => ['required', 'boolean'],
            'country' => ['sometimes', 'string', 'nullable'],
            'city' => ['sometimes', 'string', 'nullable'],
            'state' => ['sometimes', 'string', 'nullable'],
            'zip' => ['sometimes', 'string', 'nullable'],
            'address' => ['sometimes', 'string', 'nullable'],
            'email' => ['sometimes', 'nullable', 'email', Rule::unique('branches', 'email')->whereNull('deleted_at')->ignore($this->id)],
            'phone' => ['sometimes', 'nullable', 'string', Rule::unique('branches', 'phone')->whereNull('deleted_at')->ignore($this->id)],
            'mobile' => ['sometimes', 'nullable', 'string', Rule::unique('branches', 'mobile')->whereNull('deleted_at')->ignore($this->id)],
            'company_id'       => ['required', 'integer'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('edit-branch');
    }
}
