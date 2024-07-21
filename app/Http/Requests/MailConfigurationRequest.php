<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MailConfigurationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'mail_mailer' => ['required', 'regex:/^\S*$/u', 'min:2', 'max:255'],
            'mail_host' => ['required', 'regex:/^\S*$/u', 'min:2', 'max:255'],
            'mail_port' => ['required', 'regex:/^\S*$/u', 'numeric', 'min:2'],
            'mail_username' => ['required', 'regex:/^\S*$/u', 'string', 'min:2', 'max:255'],
            'mail_password' => ['required', 'regex:/^\S*$/u', 'string', 'min:2', 'max:255'],
            'mail_encryption' => ['required', 'regex:/^\S*$/u', 'min:2', 'max:255'],
            'mail_from_address' => ['required', 'email', 'string', 'min:2', 'max:255']
        ];
    }
}