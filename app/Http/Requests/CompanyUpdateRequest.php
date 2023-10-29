<?php

namespace App\Http\Requests;

use App\Contracts\ResponseData;
use App\Services\Utilities\HttpCodes;
use App\Services\Utilities\HttpResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CompanyUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * Override the default validation behavior to do nothing.
     */
    protected function validate()
    {
        // Do nothing
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('company');
        return [
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email|unique:companies,email,' . $id,
            'website' => 'sometimes|required|url|unique:companies,website,' . $id,
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
