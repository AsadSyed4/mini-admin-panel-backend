<?php

namespace App\Http\Controllers\API;

use App\Models\Employee;
use App\Contracts\ResponseData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Utilities\HttpCodes;
use App\Services\Utilities\HttpMessages;
use App\Services\Utilities\HttpResponse;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Http\Requests\EmployeeRegisterRequest;
use App\Models\Company;

class EmployeeController extends Controller
{
    /**
     * Get a listing of the employees.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index():JsonResponse
    {
        try {
            $employees = Employee::orderBy('created_at', 'desc')->with([
                'company' => function ($query) {
                    $query->select('id', 'name');
                }
            ])->get()->toArray();
            $companies = Company::get()->toArray();
            $data=[
                'employees'=>$employees,
                'companies'=>$companies,
            ];
            if (!$employees && !$companies) {
                return HttpResponse::json(new ResponseData(HttpMessages::ServiceUnavailable->value, HttpCodes::ServiceUnavailable, []));
            }
            return HttpResponse::json(new ResponseData(HttpMessages::Found->value, HttpCodes::Ok, $data));
        } catch (\Exception $e) {
            return HttpResponse::json(new ResponseData(HttpMessages::ServiceUnavailable->value . ':' . $e->getMessage(), HttpCodes::ServiceUnavailable, []));
        }
    }

    /**
     * Store a newly created employee.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(EmployeeRegisterRequest $request):JsonResponse
    {
        try {
            $employee = Employee::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'company_id' => $request->company_id,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);
            if (!$employee) {
                return HttpResponse::json(new ResponseData(HttpMessages::ServiceUnavailable->value, HttpCodes::ServiceUnavailable, []));
            }
            return HttpResponse::json(new ResponseData(HttpMessages::Created->value, HttpCodes::Created, $employee->toArray()));
        } catch (\Exception $e) {
            return HttpResponse::json(new ResponseData(HttpMessages::ServiceUnavailable->value . ':' . $e->getMessage(), HttpCodes::ServiceUnavailable, []));
        }
    }

    /**
     * Display the specified employee.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $employee):JsonResponse
    {
        if (!isset($employee) || empty($employee))
            return HttpResponse::json(new ResponseData(HttpMessages::IdMissing->value, HttpCodes::UnprocessableEntity, []));

        try {
            $employee = Employee::find($employee)->toArray();
            if (!$employee) {
                return HttpResponse::json(new ResponseData(HttpMessages::NotFound->value, HttpCodes::NotFound, []));
            }
            return HttpResponse::json(new ResponseData(HttpMessages::Found->value, HttpCodes::Ok, $employee));
        } catch (\Exception $e) {
            return HttpResponse::json(new ResponseData(HttpMessages::ServiceUnavailable->value . ':' . $e->getMessage(), HttpCodes::ServiceUnavailable, []));
        }
    }

    /**
     * Update the specified company.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(string $employee,EmployeeUpdateRequest $request):JsonResponse
    {
        if (!isset($employee) || empty($employee))
            return HttpResponse::json(new ResponseData(HttpMessages::IdMissing->value, HttpCodes::UnprocessableEntity, []));
        try {
            $employee = Employee::find($employee);
            if (!$employee) {
                return HttpResponse::json(new ResponseData(HttpMessages::NotFound->value, HttpCodes::NotFound, []));
            }
            $params=$request->all();
            $employee->update($params);

            return HttpResponse::json(new ResponseData(HttpMessages::Updated->value, HttpCodes::Ok, $employee->toArray()));
        } catch (\Exception $e) {
            return HttpResponse::json(new ResponseData(HttpMessages::ServiceUnavailable->value . ':' . $e->getMessage(), HttpCodes::ServiceUnavailable, []));
        }
    }

    /**
     * Remove the specified employee.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $employee):JsonResponse
    {
        if (!isset($employee) || empty($employee))
            return HttpResponse::json(new ResponseData(HttpMessages::IdMissing->value, HttpCodes::UnprocessableEntity, []));
        try {
            $employee = Employee::find($employee);

            if (!$employee) {
                return HttpResponse::json(new ResponseData(HttpMessages::NotFound->value, HttpCodes::NotFound, []));
            }

            $deleted = $employee->delete();
            if (!$deleted) {
                return HttpResponse::json(new ResponseData(HttpMessages::ServiceUnavailable->value, HttpCodes::ServiceUnavailable, []));
            }
            return HttpResponse::json(new ResponseData(HttpMessages::Deleted->value, HttpCodes::Ok, []));
        } catch (\Exception $e) {
            return HttpResponse::json(new ResponseData(HttpMessages::ServiceUnavailable->value . ':' . $e->getMessage(), HttpCodes::ServiceUnavailable, []));
        }
    }
}
