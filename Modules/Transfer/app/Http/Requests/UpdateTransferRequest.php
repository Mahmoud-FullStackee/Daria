<?php

namespace Modules\Transfer\Http\Requests;

use App\Enums\ItemTypesEnum;
use App\Enums\ProductTypesEnum;
use App\Traits\ValidationErrorResponseTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdateTransferRequest extends FormRequest
{
    use ValidationErrorResponseTrait;
    public function rules()
    {
        return [
            'doc_invoice_number'  => ['sometimes', 'nullable', Rule::unique('transfers', 'doc_invoice_number')->withoutTrashed()->ignore($this->transfer)],
            'from_warehouse_id' => ['required', 'integer', 'different:to_warehouse_id'],
            'to_warehouse_id' => ['required', 'integer', 'different:from_warehouse_id'],
            'remarks'       => ['string', 'nullable'],
            'date' => ['required', 'date'],
            'tax' => ['required', 'numeric', 'min:0'],
            'pipeline_id' => ['sometimes', 'nullable', 'integer'],
            'stage_id' => ['sometimes', 'nullable', 'integer'],
            'grand_total' => ['required', 'numeric'],
            'discount_type' => ['required', 'integer', Rule::in([1, 2])],
            'discount' => ['required', 'numeric', 'min:0'],
            'delegate_id' => ['sometimes', 'integer', 'nullable'],
            'commission_type' => ['required', 'integer', Rule::in([1, 2])],
            'shipping' => ['required', 'numeric', 'min:0'],
            'other_expenses' => ['required', 'numeric', 'min:0'],

            'details' => ['required', 'array'],
            'details.*' => ['required', 'array'],
            'details.*.id' => ['sometimes', 'integer', 'nullable'],
            'details.*.detailable_id' => ['sometimes', 'integer', 'nullable'],
            'details.*.detailable_type' => ['sometimes', 'string', 'nullable'],
            'details.*.warehouse_id' => ['required', 'integer'],
            'details.*.item_id' => ['required', 'integer'],
            'details.*.variant_id' => ['sometimes', 'integer', 'nullable'],
            'details.*.patch_id' => ['sometimes', 'integer', 'nullable'],
            'details.*.unit_id' => ['required', 'integer'],
            'details.*.amount' => ['required', 'numeric', 'min:0'],
            'details.*.discount' => ['required', 'numeric', 'min:0'],
            'details.*.discount_type' => ['required', 'integer', Rule::in([1, 2])],
            'details.*.tax' => ['required', 'numeric', 'min:0'],
            'details.*.tax_type' => ['required', 'integer', Rule::in([1, 2])],
            'details.*.total' => ['required', 'numeric'],
            'details.*.quantity' => ['required', 'numeric', 'min:1'],
            'details.*.production_date' => ['nullable', 'date', 'date_format:Y-m-d'],
            'details.*.expired_date' => ['nullable', 'date', 'date_format:Y-m-d', 'after:details.*.production_date'],
            'details.*.product_type' => ['required', 'integer', Rule::in([ProductTypesEnum::STOCK_ITEM->value, ProductTypesEnum::CONSUMER_ITEM->value])],
            'details.*.type' => ['required', 'integer', Rule::in([ItemTypesEnum::STANDARD->value, ItemTypesEnum::VARIABLE->value, ItemTypesEnum::SERVICE->value])],

            'transfer_documents' => ['sometimes', 'array', 'nullable'],

            'deletedDetails' => ['sometimes', 'array'],
        ];
    }

    public function authorize()
    {
        return auth()->user()->is_owner || Gate::allows('edit-transfer');
    }
}