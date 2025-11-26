<table>
    <thead>
        <tr>
            <th colspan="2" style="font-size: 18px; font-weight: bold;">
                Laporan Tahunan - {{ $year }}
            </th>
        </tr>
    </thead>

    <tbody>

        @foreach ($results as $monthData)
            <tr></tr>
            <tr>
                <td colspan="2" style="font-weight: bold; background: #d9edf7;">
                    {{ $monthData['month_name'] }}
                </td>
            </tr>

            {{-- INCOME --}}
            <tr style="font-weight:bold; background:#dff0d8;">
                <td>INCOME</td>
                <td>Amount</td>
            </tr>

            @foreach ($monthData['categories'] as $item)
                @if ($item['total_credit'] > 0)
                    <tr>
                        <td>{{ $item['category_name'] }}</td>
                        <td>{{ $item['total_credit'] - $item['total_debit'] }}</td>
                    </tr>
                @endif
            @endforeach

            <tr style="font-weight:bold; background:#c8e5bc;">
                <td>Total Income</td>
                <td>{{ $monthData['total_credit'] }}</td>
            </tr>

            {{-- EXPENSES --}}
            <tr style="font-weight:bold; background:#f2dede;">
                <td>EXPENSES</td>
                <td>Amount</td>
            </tr>

            @foreach ($monthData['categories'] as $item)
                @if ($item['total_debit'] > 0)
                    <tr>
                        <td>{{ $item['category_name'] }}</td>
                        <td>-{{ $item['total_debit'] - $item['total_credit'] }}</td>
                    </tr>
                @endif
            @endforeach

            <tr style="font-weight:bold; background:#ebcccc;">
                <td>Total Expenses</td>
                <td>-{{ $monthData['total_debit'] }}</td>
            </tr>

            {{-- NET INCOME --}}
            <tr style="font-weight:bold; background:#fcf8e3;">
                <td>Net Income</td>
                <td>{{ $monthData['net_income'] }}</td>
            </tr>
        @endforeach

    </tbody>
</table>
