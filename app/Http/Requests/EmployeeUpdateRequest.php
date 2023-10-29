<?php

namespace App\Http\Requests;

use App\Contracts\ResponseData;
use App\Services\Utilities\HttpCodes;
use App\Services\Utilities\HttpResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmployeeUpdateRequest extends FormRequest
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
        $id = $this->route('employee');
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'company_id' => 'required|numeric|exists:companies,id',
            'email' => 'required|email|unique:employees,email,'.$id,
            'phone' => 'required|regex:/^\+?\d{10}$/|unique:employees,phone,'.$id,
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
