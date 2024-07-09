<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Account\CreateRequest;
use App\Http\Requests\Account\UpdateRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $accounts = Account::orderBy('name', 'ASC');

        if (!empty($request->keyword)) {
            $accounts = $accounts->where('name', 'LIKE', '%' . $request->keyword . '%');
        }

        if (!empty($request->type)) {
            $accounts = $accounts->whereType($request->type);
        }

        $accounts = $accounts->paginate(25);
        $accounts = $accounts->map(fn($account) => new AccountResource($account));

        return response()->success('Successfully get accounts', $accounts);
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
        $accountRequest = $request->validated();

        try {
            $account = Account::create($accountRequest);
            return response()->success('Account successfully created', new AccountResource($account));

        } catch (Throwable $t) {
            return response()->failed('Failed to create account');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $account = Account::find($id);
        if (empty($account)) {
            return response()->failed('Account not found', NULL, JsonResponse::HTTP_NOT_FOUND);
        }

        return response()->success('Successfully get account', new AccountResource($account));
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
        $account = Account::find($id);
        if (empty($account)) {
            return response()->failed('Account not found', NULL, JsonResponse::HTTP_NOT_FOUND);
        }

        $accountRequest = $request->validated();

        try {
            $account->update($accountRequest);
            return response()->success('Account successfully updated', new AccountResource($account));

        } catch (Throwable $t) {
            return response()->failed('Failed to update account');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $account = Account::find($id);
        if (empty($account)) {
            return response()->failed('Account not found', NULL, JsonResponse::HTTP_NOT_FOUND);
        }

        try {
            $account->delete();
            return response()->success('Account successfully deleted');

        } catch (Throwable $t) {
            return response()->failed('Failed to delete account');
        }
    }
}
