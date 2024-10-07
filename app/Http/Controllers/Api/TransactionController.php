<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\CreateRequest;
use App\Http\Requests\Transaction\UpdateRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Models\TransactionFee;
use App\Services\Transaction\BalanceAdjustmentService;
use App\Services\Transaction\CalculatorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Throwable;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $transactions = Transaction::with(['account', 'category', 'fees'])
            ->when(!empty($request->start_date), fn($query) => $query->where('date', '>=', $request->start_date))
            ->when(!empty($request->end_date), fn($query) => $query->where('date', '<=', $request->end_date))
            ->orderBy('date', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate(25);

        $transactions = $transactions->map(fn($transaction) => new TransactionResource($transaction));

        return response()->success('Successfully get transactions', $transactions);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        $transactionRequest = $request->validated();

        DB::beginTransaction();
        try {
            $transaction = Transaction::create(Arr::except($transactionRequest, ['fees']));

            if (count($transactionRequest['fees'] ?? []) > 0) {
                $fees = Arr::map($transactionRequest['fees'], fn($fee) => new TransactionFee($fee));
                $transaction->fees()->saveMany($fees);
            }

            (new CalculatorService($transaction))->calculateTotalAmount();
            (new BalanceAdjustmentService($transaction))->run();

            DB::commit();
            return response()->success('Transaction successfully created', new TransactionResource($transaction));

        } catch (Throwable $t) {
            DB::rollBack();
            return response()->failed('Failed to create transaction');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = Transaction::with(['account', 'category', 'fees'])->find($id);
        if (empty($transaction)) {
            return response()->failed('Transaction not found', NULL, JsonResponse::HTTP_NOT_FOUND);
        }

        return response()->success('Successfully get account', new TransactionResource($transaction));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        abort(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $transaction = Transaction::with(['account', 'category', 'fees'])->find($id);
        if (empty($transaction)) {
            return response()->failed('Transaction not found', NULL, JsonResponse::HTTP_NOT_FOUND);
        }

        $transactionRequest = $request->validated();

        DB::beginTransaction();
        try {
            $transaction->update(Arr::except($transactionRequest, ['fees']));

            $currentFeeIds = $transaction->fees()->pluck('id')->toArray();
            $incomingFeeIds = collect($transactionRequest['fees'] ?? [])->pluck('id')->filter()->toArray();
            $feeIdsToDelete = array_diff($currentFeeIds, $incomingFeeIds);
            TransactionFee::destroy($feeIdsToDelete);

            foreach (($transactionRequest['fees'] ?? []) as $feeRequest) {
                if (isset($feeRequest['id'])) {
                    $transactionFee = TransactionFee::find($feeRequest['id']);
                    if (empty($transactionFee)) {
                        DB::rollBack();
                        return response()->failed('Fee not found', NULL, JsonResponse::HTTP_NOT_FOUND);
                    }

                    $transactionFee->update($feeRequest);
                }
            }

            $transaction->total_amount = (new CalculatorService($transaction))->calculateTotalAmount();
            $transaction->save();

            DB::commit();

            return response()->success('Transaction successfully updated', new TransactionResource($transaction));

        } catch (Throwable $t) {
            DB::rollBack();
            return response()->failed('Failed to update transaction');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaction = Transaction::with(['account', 'category', 'fees'])->find($id);
        if (empty($transaction)) {
            return response()->failed('Transaction not found', NULL, JsonResponse::HTTP_NOT_FOUND);
        }

        try {
            $transaction->fees()->delete();
            $transaction->delete();
            return response()->success('Transaction successfully deleted');

        } catch (Throwable $t) {
            return response()->failed('Failed to delete transaction');
        }
    }
}
