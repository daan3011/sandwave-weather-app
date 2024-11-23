<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class ListWeatherReadingsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'start_date' => $this->parseIsoDate($this->start_date),
            'end_date'   => $this->parseIsoDate($this->end_date),
        ]);
    }

    private function parseIsoDate(?string $date): ?string
    {
        if ($date) {
            try {
                return Carbon::parse($date)->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                return null;
            }
        }

        return null;
    }

    public function rules()
    {
        return [
            'weather_monitor_id' => 'required|integer|exists:weather_monitors,id',
            'start_date'         => 'nullable|date_format:Y-m-d H:i:s',
            'end_date'           => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:start_date',
            'per_page'           => 'nullable|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'start_date.date_format' => 'The start date must be in the format yyyy-mm-dd hh:mm:ss',
            'end_date.date_format'   => 'The end date must be in the format yyyy-mm-dd hh:mm:ss',
        ];
    }

}
