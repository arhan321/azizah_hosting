<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomOrderRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|min:20|max:2000',
            'dimensions' => 'nullable|string|max:255',
            'color_preference' => 'nullable|string|max:255',
            'deadline' => 'nullable|date|after:today',
            'brief' => 'nullable|string|max:2000',
            'files' => 'nullable|array',
            'files.*' => 'file|mimes:jpeg,png,jpg,pdf|max:10240', // 10MB
        ];
    }

    /**
     * Get custom messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama design wajib diisi',
            'name.max' => 'Nama design maksimal 255 karakter',
            'description.required' => 'Deskripsi wajib diisi',
            'description.min' => 'Deskripsi minimal 20 karakter',
            'description.max' => 'Deskripsi maksimal 2000 karakter',
            'dimensions.max' => 'Dimensi maksimal 255 karakter',
            'color_preference.max' => 'Preferensi warna maksimal 255 karakter',
            'deadline.date' => 'Deadline harus format tanggal yang valid',
            'deadline.after' => 'Deadline harus lebih besar dari hari ini',
            'brief.max' => 'Brief maksimal 2000 karakter',
            'files.*.mimes' => 'File harus berformat: jpeg, png, jpg, atau pdf',
            'files.*.max' => 'Ukuran file maksimal 10MB',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('deadline') && $this->deadline === '') {
            $this->merge(['deadline' => null]);
        }
    }
}
