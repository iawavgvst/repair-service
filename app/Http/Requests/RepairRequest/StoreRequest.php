<?php

namespace App\Http\Requests\RepairRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации для создания заявки
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'problem_text' => 'required|string|min:10',
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
            'client_name.required' => 'Пожалуйста, укажите имя.',
            'client_name.string' => 'Имя должно быть текстовым значением.',
            'client_name.max' => 'Имя не должно превышать 255 символов.',

            'phone.required' => 'Пожалуйста, укажите номер телефона.',
            'phone.string' => 'Номер телефона должен быть текстовым значением.',
            'phone.max' => 'Номер телефона не должен превышать 20 символов.',

            'address.required' => 'Пожалуйста, укажите адрес для выезда мастера.',
            'address.string' => 'Адрес должен быть текстовым значением.',
            'address.max' => 'Адрес не должен превышать 500 символов.',

            'problem_text.required' => 'Пожалуйста, опишите проблему, чтобы мастер знал детали.',
            'problem_text.string' => 'Описание проблемы должно быть текстовым значением.',
            'problem_text.min' => 'Описание проблемы должно содержать минимум 10 символов.',
        ];
    }
}
