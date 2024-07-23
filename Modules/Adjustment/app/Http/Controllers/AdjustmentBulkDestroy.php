<?php

namespace Modules\Adjustment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Modules\Adjustment\Models\Adjustment;

class AdjustmentBulkDestroy extends Controller
{
    public function __invoke(Request $request)
    {
        if (!auth()->user()->is_owner)  abort_if(Gate::denies('bulk-delete-adjustment'), Response::HTTP_FORBIDDEN, __('permission::messages.gate_denies'));
        try {
            Adjustment::whereIn('id', $request->ids)->delete();
            return $this->success(__('status.deleted_selected_success'));
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->error(__('status.delete_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}