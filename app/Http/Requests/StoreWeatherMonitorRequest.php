<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWeatherMonitorRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'city'             => 'required|string|max:255|unique:weather_monitors,city',
            'interval_minutes' => 'required|integer|min:5|max:1440',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('city')) {
            $this->merge([
                'city' => ucfirst(strtolower($this->city)),
            ]);
        }
    }
}

