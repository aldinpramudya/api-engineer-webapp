<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MonthlyCategoryReportExport implements FromArray, WithStyles, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $income;
    protected $expenses;
    protected $totalDebit;
    protected $totalCredit;
    protected $netIncome;


    public function __construct($income, $expenses, $totalDebit, $totalCredit, $netIncome)
    {
        $this->income      = $income;
        $this->expenses    = $expenses;
        $this->totalDebit  = $totalDebit;
        $this->totalCredit = $totalCredit;
        $this->netIncome   = $netIncome;
    }

    public function array(): array
    {
        $rows = [];

        // Header
        $rows[] = ["Category", "Amount"];

        // INCOME TITLE
        $rows[] = ["INCOME", ""];

        // INCOME ROWS
        foreach ($this->income as $item) {
            $rows[] = [
                $item->category_name,
                number_format($item->total_credit - $item->total_debit)
            ];
        }

        // TOTAL INCOME
        $rows[] = ["Total Income", number_format($this->totalCredit)];

        // EXPENSES TITLE
        $rows[] = ["EXPENSES", ""];

        // EXPENSE ROWS
        foreach ($this->expenses as $item) {
            $rows[] = [
                $item->category_name,
                "-" . number_format($item->total_debit - $item->total_credit)
            ];
        }

        // TOTAL EXPENSES
        $rows[] = ["Total Expenses", "-" . number_format($this->totalDebit)];

        // NET INCOME ROW
        $rows[] = ["Net Income", number_format($this->netIncome)];

        return $rows;
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();

        // HEADER STYLE
        $sheet->getStyle("A1:B1")->getFont()->setBold(true);

        // AUTO WIDTH
        foreach (range('A', 'B') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // BORDER ALL CELLS
        $sheet->getStyle("A1:B{$highestRow}")
            ->getBorders()->getAllBorders()->setBorderStyle('thin');

        // INCOME TITLE (ROW 2)
        $sheet->getStyle("A2:B2")->getFont()->setBold(true);
        $sheet->getStyle("A2:B2")->getFill()->setFillType('solid')
            ->getStartColor()->setARGB('90EE90'); // Green

        // EXPENSES TITLE ROW
        $expensesTitleRow = count($this->income) + 4; 
        $sheet->getStyle("A{$expensesTitleRow}:B{$expensesTitleRow}")
            ->getFont()->setBold(true);
        $sheet->getStyle("A{$expensesTitleRow}:B{$expensesTitleRow}")
            ->getFill()->setFillType('solid')
            ->getStartColor()->setARGB('FF7F7F'); // Red
        
        return [];
    }

    public function title(): string
    {
        return "Profit & Loss Report";
    }
}
