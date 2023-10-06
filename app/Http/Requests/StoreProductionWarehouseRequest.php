<?php

namespace App\Http\Requests;


use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreProductionWarehouseRequest extends FormRequest
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
            'count_id'          =>  'required|unique:production_warehouses',
            'code'              =>  'required|unique:production_warehouses',
            'production_id'     =>  'required|exists:productions,id',
            'release_date'      =>  'required|date_format:d-m-Y',
            'released_by'       =>  'required',
            'weight'            =>  'required|decimal:0,4',
            'note'              =>  'required',
            'user_id'           =>  'required|exists:users,id',
        ];
    }


    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' =>  auth()->id(),
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
