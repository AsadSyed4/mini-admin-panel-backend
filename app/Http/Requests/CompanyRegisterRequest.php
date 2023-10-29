<?php

namespace App\Http\Requests;

use App\Contracts\ResponseData;
use App\Services\Utilities\HttpCodes;
use App\Services\Utilities\HttpResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CompanyRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:companies,email',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|dimensions:min_width=100,min_height=100',
            'website' => 'required|url|unique:companies,website',
        ];
    }

    /**
     * Return failed validation response
     *
     * @return \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator):HttpResponseException
    {
        throw new HttpResponseException(
            HttpResponse::json(
                new ResponseData(implode(', ', $validator->errors()->all()), HttpCodes::UnprocessableEntity, [])
            )
        );
    }
}
