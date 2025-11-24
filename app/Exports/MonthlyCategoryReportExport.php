<?php

namespace App\Exports;

use App\Models\CategoryCoa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MonthlyCategoryReportExport implements FromCollection, WithHeadings, WithColumnWidths
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

    public function headings(): array
    {
        return [
            'Category ID',
            'Category Name',
            'Total Debit',
            'Total Credit'
        ];
    }

    // Ukuran kolom
    public function columnWidths(): array
    {
        return [
            'A' => 10, 
            'B' => 20, 
            'C' => 20, 
            'D' => 20, 
        ];
    }

    public function collection()
    {
        return CategoryCoa::with(['masterCoa.transactions'])
            ->get()
            ->map(function ($category) {

                $transactions = $category->masterCoa
                    ->flatMap(function ($coa) {
                        return $coa->transactions
                            ->whereBetween('date', [
                                "{$this->year}-{$this->month}-01",
                                "{$this->year}-{$this->month}-31",
                            ]);
                    });

                return [
                    'category_id'   => $category->id,
                    'category_name' => $category->name_category,
                    'total_debit'   => $transactions->sum('debit'),
                    'total_credit'  => $transactions->sum('credit'),
                ];
            });
    }
}
