<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with("masterCoa")->get();
        return response()->json([
            "message" => "Data Transactions Shown",
            "data" => $transactions,
        ], 201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $transactionDate = Carbon::now();
        $newTransaction = Transaction::create([
            "masters_coa_id" => $request->masters_coa_id,
            "date" => $transactionDate,
            "description" => $request->description,
            "debit" => $request->debit,
            "credit" => $request->credit,
        ]);
        
        return response()->json([
            "message" => "New Data Transaction Added",
            "data" => $newTransaction,
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = Transaction::with("masterCoa")->find($id);
        if (!$transaction) {
            return response()->json([
                "message" => "Data Transaction not found"
            ], 404);
        }

        return response()->json([
            "message" => "Data Transaction found",
            "data" => $transaction,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, string $id)
    {
        $updateTransaction = Transaction::with("masterCoa")->find($id);
        if(!$updateTransaction){
            return response()->json([
                "message" => "Data Transaction Not Found",
            ], 404);
        }

        $updateTransaction->update($request->validated());
        return response()->json([
            "message" => "Data Transaction Update",
            "data" => $updateTransaction,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transaction = Transaction::with("masterCoa")->find($id);
        if (!$transaction) {
            return response()->json([
                "message" => "Data Transaction not found"
            ], 404);
        }
        $transaction->delete();
        return response()->json([
            "message" => "Data Transaction Successfully Deleted",
            "data" => $transaction,
        ]);
    }
}
