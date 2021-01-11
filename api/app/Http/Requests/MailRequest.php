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
        if (! auth()->check()) return false;
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
    public function rules(): array
    {
        switch ($this->getMethod())
        {
            case 'POST':
                return [
                    'from_email' => 'required|email',
                    'to_email' => 'required|email',
                    'subject' => 'required|string',
                    'html' => 'required|string',
                    'attachments.*' => 'mimes:pdf,xls,xlsx,doc,docx,jpeg,png|max:2000' // Kb
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

    /**
     * Set custom messages for validator errors.
     *
     * @return array
     */    public function messages(): array
    {
        return [
            'from_email.required' => 'Sender\'s email is required.',
            'to_email.required' => 'Recipient\'s email is required.',
            'subject.required' => 'Subject is required.',
            'html.required' => 'Mail Body is required.',
            'attachments.*.mimes' => 'We only support attachments of: :values',
            'attachments.*.max' => 'Each attachment may not be greater than :max kilobytes.'
        ];
    }
}
