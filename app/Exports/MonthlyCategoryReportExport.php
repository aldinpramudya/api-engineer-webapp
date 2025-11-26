<?php

namespace App\Exports;

use App\Models\CategoryCoa;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class MonthlyCategoryReportExport implements FromView, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year  = $year;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 20,
            'C' => 20,
        ];
    }


    public function view(): View
    {
        $start = "{$this->year}-{$this->month}-01";
        $end   = date("Y-m-t", strtotime($start));

        $categories = CategoryCoa::get()->map(function ($category) use ($start, $end) {

            $total = Transaction::whereHas('masterCoa', function ($q) use ($category) {
                $q->where('category_coa_id', $category->id);
            })
                ->whereBetween('date', [$start, $end])
                ->selectRaw('SUM(debit) AS total_debit, SUM(credit) AS total_credit')
                ->first();

            return [
                'category_id'   => $category->id,
                'category_name' => $category->name_category,
                'total_debit'   => $total->total_debit ?? 0,
                'total_credit'  => $total->total_credit ?? 0,
            ];
        });

        $grandTotalDebit  = $categories->sum('total_debit');
        $grandTotalCredit = $categories->sum('total_credit');
        $netIncome        = $grandTotalCredit - $grandTotalDebit;

        return view('exports.monthly_total', [
            'categories'        => $categories,
            'grandTotalDebit'   => $grandTotalDebit,
            'grandTotalCredit'  => $grandTotalCredit,
            'netIncome'         => $netIncome,
            'month'             => $this->month,
            'year'              => $this->year,
        ]);
    }
}
