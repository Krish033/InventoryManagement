<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class MainPurchaseRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array {
        return [
            'tranDate' => 'required',
            'supplierId' => 'required',
            'mop' => 'required',
            'invoiceNo' => 'nullable',
            'taxable' => 'required',
            'taxAmount' => 'required',
            'TotalAmount' => 'required',
            'paidAmount' => 'required',
            'balanceAmount' => 'required',
            'products' => 'nullable',
        ];
    }
}