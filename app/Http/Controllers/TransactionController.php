<?php

namespace App\Http\Controllers;

use App\Exports\MonthlyCategoryReportExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Models\CategoryCoa;
use App\Models\MasterCoa;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with(["masterCoa.categoryCoa"])->get();
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

        $masterCoa = MasterCoa::with('categoryCoa')->find($request->masters_coa_id);
        $typeCategory = $masterCoa->categoryCoa->type_category;

        $debit = 0;
        $credit = 0;

        if ($typeCategory === 'Income') {
            $credit = $request->amount;
            $debit = 0;
        } elseif ($typeCategory === 'Expenses') {
            $debit = $request->amount;
            $credit = 0;
        }

        $newTransaction = Transaction::create([
            "masters_coa_id" => $request->masters_coa_id,
            "date" => $transactionDate,
            "description" => $request->description,
            "debit" => $debit,
            "credit" => $credit,
        ]);

        return response()->json([
            "message" => "New Data Transaction Added",
            "data" => $newTransaction,
        ], 201);
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
        $transactionDate = $request->date ? Carbon::parse($request->date) : Carbon::now();

        // data category
        $masterCoa = MasterCoa::with('categoryCoa')->find($request->masters_coa_id);

        if (!$masterCoa || !$masterCoa->categoryCoa) {
            return response()->json([
                "message" => "Master COA atau Category tidak ditemukan"
            ], 404);
        }

        // Mengambil Data Category
        $typeCategory = $masterCoa->categoryCoa->type_category;
        // Tentukan debit & credit
        $debit = 0;
        $credit = 0;

        if ($typeCategory === 'Income') {
            $credit = $request->amount;
            $debit = 0;
        } elseif ($typeCategory === 'Expenses') {
            $debit = $request->amount;
            $credit = 0;
        }

        $updateTransaction->update([
            "masters_coa_id" => $request->masters_coa_id,
            "date" => $transactionDate,
            "description" => $request->description,
            "debit" => $debit,
            "credit" => $credit,
        ]);

        if (!$updateTransaction) {
            return response()->json([
                "message" => "Data Transaction Not Found",
            ], 404);
        }
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

    // get Transaction By Month
    public function getTransactionsByMonth(Request $request)
    {
        $month = $request->month;
        $year = $request->year;

        $transaction = Transaction::with('masterCoa')
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get();

        return response()->json([
            'message' => 'Montly Transaction Fetched',
            'data' => $transaction
        ]);
    }

    // Get total debit and credit
    public function getTotalProfitLoss()
    {
        $totalCredit = Transaction::sum('credit');
        $totalDebit = Transaction::sum('debit');
        $netIncome = $totalDebit - $totalCredit;

        return response()->json([
            'message' => 'Total Net Income',
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
            'net_income' => $netIncome,
        ]);
    }

    public function getMonthlyTotal(Request $request)
    {
        $month = $request->month;
        $year  = $request->year;

        $start = "$year-$month-01";
        $end   = date("Y-m-t", strtotime($start));

        $categories = CategoryCoa::get()->map(function ($category) use ($start, $end) {

            $total = Transaction::whereHas('masterCoa', function ($q) use ($category) {
                $q->where('category_coa_id', $category->id);
            })
                ->whereBetween('date', [$start, $end])
                ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
                ->first();

            return [
                'category_id'   => $category->id,
                'category_name' => $category->name_category,
                'total_debit'   => $total->total_debit ?? 0,
                'total_credit'  => $total->total_credit ?? 0,
            ];
        });

        // Mengghitung total keseluruhan
        $grandTotalDebit = $categories->sum('total_debit');
        $grandTotalCredit = $categories->sum('total_credit');
        $netIncome = $grandTotalCredit - $grandTotalDebit;

        return response()->json([
            'message' => "Monthly Total",
            'data' => $categories,
            'total_all_debit' => $grandTotalDebit,
            'total_all_credit' => $grandTotalCredit,
            'net_income' => $netIncome,
        ]);
    }

    public function exportExcel(Request $request)
    {

        $month = $request->month;
        $year  = $request->year;

        return Excel::download(
            new MonthlyCategoryReportExport($month, $year),
            "Export-Profit-Loss-{$month}-{$year}.xlsx"
        );
    }
}
