<?php

namespace Modules\Department\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Modules\Department\Models\Department;
use Modules\Department\Http\Requests\StoreDepartmentRequest;
use Modules\Upload\Http\Controllers\FilesAssign;

class DepartmentStore extends Controller
{
    public function __invoke(StoreDepartmentRequest $request)
    {
        try {
            $request = $request->validated();
            $department = DB::transaction(function () use ($request) {
                $department = Department::create(Arr::except($request, ['logo', 'department']));

                if (isset($request['logo']) && !is_null($request['logo']) && !array_key_exists('fake', $request['logo'])) {
                    (new FilesAssign)($request['logo'], $department, 'departments', 'logo');
                }
                return $department;
            });
            return $this->success(__('status.created', ['name' => $department['name'], 'module' => __('modules.department')]));
        } catch (\Illuminate\Database\QueryException $e) {
            $this->error(__('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $this->error(trans('status.create_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
