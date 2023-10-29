<?php

namespace App\Http\Controllers\API;

use App\Models\Company;
use App\Models\Employee;
use App\Contracts\ResponseData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Utilities\HttpCodes;
use App\Services\Utilities\HttpMessages;
use App\Services\Utilities\HttpResponse;

class DashboardController extends Controller
{
    /**
     * Get companies and employees count and latest 5 companies.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(): JsonResponse
    {
        try {
            $company_count = Company::count();
            $employee_count = Employee::count();

            $companies = Company::orderBy('created_at', 'desc')->withCount('employees')
                ->take(5)
                ->get()->toArray();
            $data = [
                'company_count' => $company_count,
                'employee_count' => $employee_count,
                'companies' => $companies,
            ];
            return HttpResponse::json(new ResponseData(HttpMessages::Found->value, HttpCodes::Ok, $data));
        } catch (\Exception $e) {
            return HttpResponse::json(new ResponseData(HttpMessages::ServiceUnavailable->value . ':' . $e->getMessage(), HttpCodes::ServiceUnavailable, []));
        }
    }
}
