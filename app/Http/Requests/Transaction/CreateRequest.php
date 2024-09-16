<?php

namespace App\Http\Requests\Transaction;

use App\Models\Transaction;
use App\Models\TransactionFee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
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
            'type' => [
                'required',
                Rule::in(Transaction::TYPE_CREDIT, Transaction::TYPE_DEBIT, Transaction::TYPE_TRANSFER)
            ],
            'account_id' => [
                'required',
                'exists:App\Models\Account,id'
            ],
            'category_id' => [
                'required_if:type,' . Transaction::TYPE_CREDIT . ',' . Transaction::TYPE_DEBIT,
                'exists:App\Models\Category,id'
            ],
            'transfer_to_account_id' => [
                'required_if:type,' . Transaction::TYPE_TRANSFER,
                'exists:App\Models\Account,id',
                'different:account_id'
            ],
            'date' => [
                'required',
                'date_format:Y-m-d'
            ],
            'amount' => [
                'required'
            ],
            'note' => [
                'max:300'
            ],
            'fees' => [
                'array'
            ],
            'fees.*.type' => [
                'required_with:fees',
                Rule::in(TransactionFee::TYPE_COMMISSION, TransactionFee::TYPE_TAX)
            ],
            'fees.*.operation' => [
                'required_with:fees',
                Rule::in(TransactionFee::OPERATION_TYPE_INDUCT, TransactionFee::OPERATION_TYPE_DEDUCT)
            ],
            'fees.*.format' => [
                'required_with:fees',
                Rule::in(TransactionFee::FORMAT_PERCENT, TransactionFee::FORMAT_AMOUNT)
            ],
            'fees.*.amount' => [
                'required_with:fees'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Please choose type',
            'type.in' => 'Invalid type',
            'account_id.required' => 'Please choose source account',
            'account_id.exists' => 'Source account is not exists',
            'category_id.required_if' => 'Please choose target category',
            'category_id.exists' => 'Target category is not exists',
            'transfer_to_account_id.required_if' => 'Please choose target account',
            'transfer_to_account_id.exists' => 'Target account is not exists',
            'transfer_to_account_id.different' => 'Transfer to account cannot be same with source account',
            'date.required' => 'Please fill date',
            'date.date_format' => 'Invalid date format',
            'amount.required' => 'Please fill amount',
            'note.max' => 'Note is too long',
            'fees.array' => 'Invalid fees',
            'fees.*.type.required_with' => 'Please choose fee type',
            'fees.*.type.in' => 'Invalid fee type',
            'fees.*.operation.required_with' => 'Please choose fee operation type',
            'fees.*.operation.in' => 'Invalid fee operation type',
            'fees.*.format.required_with' => 'Please choose fee format',
            'fees.*.format.in' => 'Invalid fee format',
            'fees.*.amount.required_with' => 'Please fill fee amount'
        ];
    }
}
