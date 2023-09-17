<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreProcurementWeighbridgeRequest extends FormRequest
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
            "count_id"          =>  ['required','unique:weighbridges'],
            'code'              =>  ['required','unique:weighbridges'],
            'procurement_id'    =>  ['required','unique:weighbridges','exists:procurements,id'],
            'first_date'        =>  ['required', 'date_format:d-m-Y'],
            'first_time'        =>  ['required'],
            'first_weight'      =>  ['required','decimal:0,4'],
            'second_date'       =>  ['date_format:d-m-Y'],
            'second_weight'     =>  ['decimal:0,4'],
            'weight'            =>  ['required','decimal:0,4'],
            'bags'              =>  ['required','numeric'],
            'operator'          =>  ['required'],
            'user_id'           =>  ['required','exists:users,id'],
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
