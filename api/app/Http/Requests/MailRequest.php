<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
                    'text' => 'required|string',
                    'attachments.*' => 'mimes:pdf,xls,xlsx,doc,docx|max:500000'
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
            'from_email' => 'string|email|exists:mails,from_email',
            'to_email' => 'string|email|exists:mails,to_email',
            'subject' => 'string',
            'status' => 'in:sent,posted,failed',
            'id' => 'integer|exists:mails,id',
        ];
    }
}
