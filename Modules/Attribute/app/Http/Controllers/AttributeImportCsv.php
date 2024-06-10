<?php

namespace Modules\Attribute\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Modules\Attribute\Models\Attribute;
use Illuminate\Support\Str;

class AttributeImportCsv extends Controller
{
    public function __invoke(Request $request)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('import-csv-attribute'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            $attributes = $request->all();
            $removed = array_shift($attributes);
            $attributesArray = [];
            foreach ($attributes as $key => $value) {
                $attributesArray[] = [
                    'name' => $value['name'],
                    'email' => isset($value['email']) && filter_var($value['email'], FILTER_VALIDATE_EMAIL) ? $value['email'] : null,
                    'phone' => isset($value['phone']) ? $value['phone'] : null,
                    'mobile' => isset($value['mobile']) ? $value['mobile'] : null,
                    'country' => isset($value['country']) ? $value['country'] : null,
                    'city' => isset($value['city']) ? $value['city'] : null,
                    'state' => isset($value['state']) ? $value['state'] : null,
                    'zip' => isset($value['zip']) ? $value['zip'] : null,
                    'first_address' => isset($value['first_address']) ? $value['first_address'] : null,
                    'second_address' => isset($value['second_address']) ? $value['second_address'] : null,
                    'is_active' => isset($value['is_active']) && in_array(Str::lower($value['is_active']), Controller::ACTIVE_ARRAY) ? true : false,
                    'password' => isset($value['password']) ? $value['password'] : 'DefaultPassword1@'
                ];
            }
            $errors = [];
            foreach ($attributesArray as $attribute) {
                $validator = Validator::make($attribute, [
                    'name'          => ['required', 'string', 'min:3', 'max:100', Rule::unique('attributes', 'name')->whereNull('deleted_at')],
                    'email'     => ['sometimes', 'nullable', 'email', Rule::unique('attributes', 'email')->whereNull('deleted_at')],
                    'phone' => ['sometimes', 'nullable', 'string'],
                    'mobile' => ['sometimes', 'nullable', 'string'],
                    'country' => ['string', 'nullable'],
                    'city' => ['string', 'nullable'],
                    'state' => ['string', 'nullable'],
                    'zip' => ['string', 'nullable'],
                    'first_address' => ['string', 'nullable'],
                    'second_address' => ['string', 'nullable'],
                    'is_active'     => ['required', 'boolean'],
                    'remarks'       => ['string', 'nullable'],
                ]);

                if ($validator->fails()) {
                    $errors[$attribute['name']] = $validator->errors();
                }
            }
            if ($errors) {
                return $this->error(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            Attribute::upsert($attributesArray, ['email']);
            return $this->success(__('status.imported_csv_successfully'));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.import_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.import_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}