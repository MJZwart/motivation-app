<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMaintenanceBannerMessage extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'message' => 'required|string',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date',
        ];
    }

    public function passedValidation()
    {
        $validated = $this->validated();
        $validated['starts_at'] = Carbon::parse($validated['starts_at'])->format('Y-m-d H:i:s');
        $validated['ends_at'] = Carbon::parse($validated['ends_at'])->format('Y-m-d H:i:s');

        $this->replace($validated);
    }
}
