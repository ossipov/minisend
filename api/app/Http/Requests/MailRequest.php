<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class MailRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->getMethod())
        {
            case 'POST':
                return [
                    'from_email' => 'required|email',
                    'to_email' => 'required|email',
                    'subject' => 'required|string',
                    'html' => 'required|string',
                    'attachments.*' => 'mimes:pdf,xls,xlsx,doc,docx,jpeg,png|max:500000'
                ];
            case 'DELETE':
                return [
                    'id' => 'required|integer|exists:mails,id'
                ];
//            case 'PUT':
//            case 'PATCH':
        }
//      case 'GET':
        return [
            'from' => 'string',
            'to' => 'string',
            'subject' => 'string',
            'status' => 'in:sent,posted,failed',
            'id' => 'integer|exists:mails,id',
        ];
    }
}
