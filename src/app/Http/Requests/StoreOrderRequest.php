<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'payment_type' => 'required|in:full,dp',
            'items' => 'required|array|min:1',
            'items.*.design_id' => 'required|integer|exists:designs,id',
            'items.*.customization' => 'nullable|array',
            'items.*.customization.color' => 'nullable|string|max:7',
            'items.*.customization.width' => 'nullable|numeric|min:1|max:1000',
            'items.*.customization.height' => 'nullable|numeric|min:1|max:1000',
            'items.*.customization.unit' => 'nullable|in:cm,inch,mm',
            'items.*.customization.text_override' => 'nullable|string|max:500',
            'items.*.customization.additional_notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'payment_type.required' => 'Payment type harus dipilih (full atau dp)',
            'payment_type.in' => 'Payment type harus full atau dp',
            'items.required' => 'Minimal 1 design harus dipilih',
            'items.min' => 'Minimal 1 design harus dipilih',
            'items.*.design_id.required' => 'Design ID wajib diisi',
            'items.*.design_id.exists' => 'Design yang dipilih tidak ditemukan',
            'items.*.customization.color' => 'Warna harus format hex (contoh: #FF0000)',
            'items.*.customization.width' => 'Lebar harus angka antara 1-1000',
            'items.*.customization.height' => 'Tinggi harus angka antara 1-1000',
            'items.*.customization.unit' => 'Unit harus cm, inch, atau mm',
        ];
    }

    /**
     * Get the validated data with transformations.
     */
    public function validated(): array
    {
        $data = parent::validated();

        // Transform items to ensure customization is properly formatted
        $data['items'] = array_map(function ($item) {
            return [
                'design_id' => $item['design_id'],
                'customization' => [
                    'color' => $item['customization']['color'] ?? null,
                    'width' => $item['customization']['width'] ?? null,
                    'height' => $item['customization']['height'] ?? null,
                    'unit' => $item['customization']['unit'] ?? 'cm',
                    'text_override' => $item['customization']['text_override'] ?? null,
                    'additional_notes' => $item['customization']['additional_notes'] ?? null,
                ],
            ];
        }, $data['items']);

        return $data;
    }
}
