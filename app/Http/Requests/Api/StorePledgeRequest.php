<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StorePledgeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                 => ['required', 'string', 'max:255'],
            'pledge_text_version'  => ['sometimes', 'string', 'max:20'],
            'signature_base64'     => ['required', 'string'],
            'signed_at'            => ['required', 'date'],
            'app_version'          => ['sometimes', 'nullable', 'string', 'max:20'],
            'device_uuid'          => ['sometimes', 'nullable', 'string', 'max:100'],
            'local_uuid'           => ['sometimes', 'nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'            => 'الاسم مطلوب.',
            'signature_base64.required' => 'التوقيع مطلوب.',
            'signed_at.required'       => 'تاريخ التوقيع مطلوب.',
            'signed_at.date'           => 'تاريخ التوقيع غير صالح.',
        ];
    }
}
