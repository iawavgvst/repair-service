<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации для авторизации
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    /**
     * Кастомные сообщения
     *
     * @return array<string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Пожалуйста, введите email.',
            'email.email' => 'Введите корректный email-адрес в формате "example@some.domain".',

            'password.required' => 'Пожалуйста, введите пароль.',
        ];
    }
}
