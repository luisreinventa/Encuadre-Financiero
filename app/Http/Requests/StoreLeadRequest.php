<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'plan'  => ['required', 'string', 'in:grupal,transformacion,relanzamiento'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'El nombre es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.email'    => 'El correo no es válido.',
            'phone.required' => 'El WhatsApp es obligatorio.',
            'plan.required'  => 'Selecciona un nivel.',
            'plan.in'        => 'El plan seleccionado no es válido.',
        ];
    }
}
