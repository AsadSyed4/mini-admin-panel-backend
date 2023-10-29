<?php

namespace App\Http\Controllers\API;

use App\Models\Company;
use App\Contracts\ResponseData;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Utilities\HttpCodes;
use Illuminate\Support\Facades\Storage;
use App\Services\Utilities\HttpMessages;
use App\Services\Utilities\HttpResponse;
use App\Http\Requests\CompanyUpdateRequest;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\CompanyRegisterRequest;
use App\Notifications\NewCompanyNotification;

class CompanyController extends Controller
{
    /**
     * Get a listing of the companies.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index():JsonResponse
    {
        try {
            $companies = Company::orderBy('created_at', 'desc')->withCount('employees')->get()->toArray();
            if (!$companies) {
                return HttpResponse::json(new ResponseData(HttpMessages::ServiceUnavailable->value, HttpCodes::ServiceUnavailable, []));
            }
            return HttpResponse::json(new ResponseData(HttpMessages::Found->value, HttpCodes::Ok, $companies));
        } catch (\Exception $e) {
            return HttpResponse::json(new ResponseData(HttpMessages::ServiceUnavailable->value . ':' . $e->getMessage(), HttpCodes::ServiceUnavailable, []));
        }
    }

    /**
     * Store a newly created company.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CompanyRegisterRequest $request):JsonResponse
    {
        try {
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = str_replace(' ', '_', $request->name) . '_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('public', $filename);
                $logoPath = str_replace('public/', '', $filePath);
            }
            $company = Company::create([
                'name' => $request->name,
                'email' => $request->email,
                'logo' => isset($logoPath) ? $logoPath : null,
                'website' => $request->website,
            ]);
            if (!$company) {
                return HttpResponse::json(new ResponseData(HttpMessages::ServiceUnavailable->value, HttpCodes::ServiceUnavailable, []));
            }
            Notification::send($company, new NewCompanyNotification);
            return HttpResponse::json(new ResponseData(HttpMessages::Created->value, HttpCodes::Created, $company->toArray()));
        } catch (\Exception $e) {
            return HttpResponse::json(new ResponseData(HttpMessages::ServiceUnavailable->value . ':' . $e->getMessage(), HttpCodes::ServiceUnavailable, []));
        }
    }

    /**
     * Display the specified company.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $company):JsonResponse
    {
        if (!isset($company) || empty($company))
            return HttpResponse::json(new ResponseData(HttpMessages::IdMissing->value, HttpCodes::UnprocessableEntity, []));

        try {
            $company = Company::find($company)->toArray();
            if (!$company) {
                return HttpResponse::json(new ResponseData(HttpMessages::NotFound->value, HttpCodes::NotFound, []));
            }
            return HttpResponse::json(new ResponseData(HttpMessages::Found->value, HttpCodes::Ok, $company));
        } catch (\Exception $e) {
            return HttpResponse::json(new ResponseData(HttpMessages::ServiceUnavailable->value . ':' . $e->getMessage(), HttpCodes::ServiceUnavailable, []));
        }
    }

    /**
     * Update the specified company.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(string $company,CompanyUpdateRequest $request):JsonResponse
    {
        if (!isset($company) || empty($company))
            return HttpResponse::json(new ResponseData(HttpMessages::IdMissing->value, HttpCodes::UnprocessableEntity, []));
        try {
            $company = Company::find($company);
            if (!$company) {
                return HttpResponse::json(new ResponseData(HttpMessages::NotFound->value, HttpCodes::NotFound, []));
            }
            $params=$request->all();
            $company->update($params);

            return HttpResponse::json(new ResponseData(HttpMessages::Updated->value, HttpCodes::Ok, $company->toArray()));
        } catch (\Exception $e) {
            return HttpResponse::json(new ResponseData(HttpMessages::ServiceUnavailable->value . ':' . $e->getMessage(), HttpCodes::ServiceUnavailable, []));
        }
    }

    /**
     * Remove the specified company.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $company):JsonResponse
    {
        if (!isset($company) || empty($company))
            return HttpResponse::json(new ResponseData(HttpMessages::IdMissing->value, HttpCodes::UnprocessableEntity, []));
        try {
            $company = Company::find($company);

            $logoPath = 'public/' . $company->logo;

            if (!$company) {
                return HttpResponse::json(new ResponseData(HttpMessages::NotFound->value, HttpCodes::NotFound, []));
            }

            $deleted = $company->delete();
            if (!$deleted) {
                return HttpResponse::json(new ResponseData(HttpMessages::ServiceUnavailable->value, HttpCodes::ServiceUnavailable, []));
            }
            if (Storage::exists($logoPath)) {
                Storage::delete($logoPath);
            }
            return HttpResponse::json(new ResponseData(HttpMessages::Deleted->value, HttpCodes::Ok, []));
        } catch (\Exception $e) {
            return HttpResponse::json(new ResponseData(HttpMessages::ServiceUnavailable->value . ':' . $e->getMessage(), HttpCodes::ServiceUnavailable, []));
        }
    }
}
