<?php

namespace App\Exports;

use App\Models\CategoryCoa;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class YearlyCategoryReportExport implements FromView, WithColumnWidths
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $year;

    public function __construct($year)
    {
        $this->year = $year;
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
        $year = $this->year;
        $categories = CategoryCoa::all();

        $result = [];

        for ($month = 1; $month <= 12; $month++) {

            $start = "$year-" . str_pad($month, 2, "0", STR_PAD_LEFT) . "-01";
            $end   = date("Y-m-t", strtotime($start));

            $categoryData = $categories->map(function ($category) use ($start, $end) {
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

            $grandTotalDebit  = $categoryData->sum('total_debit');
            $grandTotalCredit = $categoryData->sum('total_credit');
            $netIncome        = $grandTotalCredit - $grandTotalDebit;

            // Skip jika bulan kosong
            if ($grandTotalDebit == 0 && $grandTotalCredit == 0) {
                continue;
            }

            $result[] = [
                'month'      => $month,
                'month_name' => date("F", mktime(0, 0, 0, $month, 1)),
                'categories' => $categoryData,
                'total_debit' => $grandTotalDebit,
                'total_credit' => $grandTotalCredit,
                'net_income' => $netIncome,
            ];
        }

        return view('exports.yearly_total', [
            'year' => $year,
            'results' => $result
        ]);
    }
}
