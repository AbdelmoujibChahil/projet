<?php
namespace App\Features\Dashboard\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PeriodRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'period' => 'nullable|in:today,7days,30days,1year',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function getPeriod()
    {
        return $this->period ?? 'today';
    }
}