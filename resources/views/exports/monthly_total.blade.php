<table>
    <thead>
        <tr>
            <th colspan="2" style="font-size: 18px; font-weight: bold; text-align:center;">
                Laporan Bulanan - {{ $month }}/{{ $year }}
            </th>
        </tr>

        <tr></tr>

        <tr style="background:#d3d3d3; font-weight:bold;">
            <th style="border:1px solid #000; padding:5px;">Category</th>
            <th style="border:1px solid #000; padding:5px;">Amount</th>
        </tr>
    </thead>

    <tbody>

        <!-- INCOME SECTION -->
        <tr style="background:#c6f6d5; font-weight:bold;">
            <td colspan="2" style="border:1px solid #000; padding:5px;">INCOME</td>
        </tr>

        @foreach ($categories->where('total_credit', '>', 0) as $item)
            <tr>
                <td style="border:1px solid #000; padding:5px;">{{ $item['category_name'] }}</td>
                <td style="border:1px solid #000; padding:5px;">
                    {{ $item['total_credit'] - $item['total_debit'] }}
                </td>
            </tr>
        @endforeach

        <!-- TOTAL INCOME -->
        <tr style="background:#9ae6b4; font-weight:bold;">
            <td style="border:1px solid #000; padding:5px;">TOTAL INCOME</td>
            <td style="border:1px solid #000; padding:5px;">
                {{ $grandTotalCredit }}
            </td>
        </tr>

        <tr></tr>

        <!-- EXPENSES SECTION -->
        <tr style="background:#fed7d7; font-weight:bold;">
            <td colspan="2" style="border:1px solid #000; padding:5px;">EXPENSES</td>
        </tr>

        @foreach ($categories->where('total_debit', '>', 0) as $item)
            <tr>
                <td style="border:1px solid #000; padding:5px;">{{ $item['category_name'] }}</td>
                <td style="border:1px solid #000; padding:5px;">
                    {{ $item['total_debit'] - $item['total_credit'] }}
                </td>
            </tr>
        @endforeach

        <!-- TOTAL EXPENSES -->
        <tr style="background:#feb2b2; font-weight:bold;">
            <td style="border:1px solid #000; padding:5px;">TOTAL EXPENSES</td>
            <td style="border:1px solid #000; padding:5px;">
                {{ $grandTotalDebit }}
            </td>
        </tr>

        <tr></tr>

        <!-- NET INCOME -->
        <tr style="background:#fefcbf; font-weight:bold;">
            <td style="border:1px solid #000; padding:5px;">NET INCOME</td>
            <td style="border:1px solid #000; padding:5px;">
                {{ $netIncome }}
            </td>
        </tr>

    </tbody>
</table>
