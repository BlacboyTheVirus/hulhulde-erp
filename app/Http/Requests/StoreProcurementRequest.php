<?php

namespace App\Http\Requests;

use App\Enums\ProcurementNext;
use App\Enums\ProcurementStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;

class StoreProcurementRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'count_id'          =>  ['required', 'unique:procurements'],
            'code'              =>  ['required', 'unique:procurements'],
            'supplier_id'       =>  ['required', 'exists:suppliers,id'],
            'input_id'          =>  ['required', 'exists:inputs,id'],
            'procurement_date'  =>  ['required', 'date_format:d-m-Y'],
            'expected_weight'   =>  ['required', 'decimal:0,4'],
            'expected_bags'     =>  ['required', 'numeric'],
            'location'          =>  ['required'],
            'status'            =>  ['required'],
            'next'              =>  ['required'],
            'user_id'           =>  ['required', 'exists:users,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' =>  auth()->id(),
            'status'  =>  ProcurementStatus::OPEN,
            'next'    =>   ProcurementNext::SECURITY,
        ]);
    }


    protected function failedValidation(Validator $validator)
    {
            $errors = $validator->errors()->all();
            $error_text = "";
            foreach ($errors as $err){
                $error_text .= $err . " ";
            }

            $response = response()->json([
                'success' => false,
                'message' => $error_text,
            ]);

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());


    }



}
