<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zestawienie zamówień - {{ $orderDate->format('d.m.Y') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            background: white;
            font-size: 14px;
        }
        
        .print-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #333;
        }
        
        .header h1 {
            font-size: 28px;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        
        .header h2 {
            font-size: 20px;
            color: #27ae60;
            margin-bottom: 10px;
        }
        
        .info-bar {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .info-item {
            text-align: center;
            flex: 1;
        }
        
        .info-item .label {
            font-size: 12px;
            color: #7f8c8d;
            text-transform: uppercase;
        }
        
        .info-item .value {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background: #27ae60;
            color: white;
            padding: 10px 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 18px;
        }
        
        .section-title i {
            margin-right: 8px;
        }
        
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .summary-table th,
        .summary-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        
        .summary-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .summary-table td {
            text-align: center;
        }
        
        .summary-table td:first-child {
            text-align: left;
        }
        
        .ward-card {
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 15px;
            page-break-inside: avoid;
        }
        
        .ward-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #27ae60;
        }
        
        .ward-name {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .ward-total {
            background: #27ae60;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        
        .diet-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .diet-table th,
        .diet-table td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .diet-table th {
            background: #e9ecef;
            font-weight: bold;
        }
        
        .diet-table td {
            text-align: center;
        }
        
        .diet-table td:first-child {
            text-align: left;
        }
        
        .total-row {
            font-weight: bold;
            background: #e9ecef;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 11px;
            color: #999;
        }
        
        .print-date {
            text-align: right;
            font-size: 11px;
            color: #999;
            margin-bottom: 15px;
        }
        
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .no-print {
                display: none;
            }
            .ward-card {
                break-inside: avoid;
                page-break-inside: avoid;
            }
            .info-bar {
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        
        {{-- Przyciski do druku (nie widoczne w wydruku) --}}
        <div class="no-print" style="margin-bottom: 20px; text-align: center;">
            <button onclick="window.print()" style="padding: 10px 20px; background: #27ae60; color: white; border: none; border-radius: 5px; cursor: pointer;">
                🖨️ Drukuj / Zapisz jako PDF
            </button>
            <button onclick="window.close()" style="padding: 10px 20px; background: #7f8c8d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
                ❌ Zamknij
            </button>
        </div>
        
        {{-- Nagłówek --}}
        <div class="header">
            <h1>🏥 ZESTAWIENIE ZAMÓWIEŃ</h1>
            <h2>System Cateringowy</h2>
            <p style="font-size: 16px; color: #555;">Dla kuchni – {{ $orderDate->format('d.m.Y') }}</p>
        </div>
        
        <div class="print-date">
            Data wydruku: {{ now()->format('d.m.Y H:i') }}
        </div>
        
        {{-- Podsumowanie --}}
        <div class="info-bar">
            <div class="info-item">
                <div class="label">Łączna liczba posiłków</div>
                <div class="value">{{ $totalAll }}</div>
            </div>
            <div class="info-item">
                <div class="label">Oddziały z zamówieniem</div>
                <div class="value">{{ $wardsSubmitted }} / {{ $totalWards }}</div>
            </div>
            <div class="info-item">
                <div class="label">Średnia na oddział</div>
                <div class="value">{{ $wardsSubmitted > 0 ? round($totalAll / $wardsSubmitted) : 0 }}</div>
            </div>
        </div>
        
        {{-- Zestawienie zbiorcze według diet (dla kuchni) --}}
        <div class="section">
            <div class="section-title">
                <i>📊</i> ZESTAWIENIE ZBIORCZE – DIETY
            </div>
            <table class="summary-table">
                <thead>
                    <tr>
                        <th>Dieta</th>
                        <th width="120">Ilość</th>
                        <th width="120">%</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($summaryByDiet as $diet => $quantity)
                    <tr>
                        <td><strong>{{ $diet }}</strong></td>
                        <td style="text-align: center;">{{ $quantity }}</td>
                        <td style="text-align: center;">{{ round(($quantity / $totalAll) * 100, 1) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot style="background: #f8f9fa; font-weight: bold;">
                    <tr>
                        <td>SUMA</td>
                        <td style="text-align: center;">{{ $totalAll }}</td>
                        <td style="text-align: center;">100%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        {{-- Szczegółowe zestawienie według oddziałów --}}
        <div class="section">
            <div class="section-title">
                <i>🏥</i> SZCZEGÓŁOWE ZESTAWIENIE – ODDZIAŁY
            </div>
            
            @forelse($wardsSummary as $wardName => $data)
            <div class="ward-card">
                <div class="ward-header">
                    <span class="ward-name">
                        <i class="fas fa-building"></i> {{ $wardName }}
                    </span>
                    <span class="ward-total">Łącznie: {{ $data['total'] }} posiłków</span>
                </div>
                <table class="diet-table">
                    <thead>
                        <tr>
                            <th>Dieta</th>
                            <th width="100">Ilość</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data['details'] as $diet => $qty)
                        <tr>
                            <td>{{ $diet }}</td>
                            <td style="text-align: center;">{{ $qty }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td><strong>RAZEM</strong></td>
                            <td style="text-align: center;"><strong>{{ $data['total'] }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @empty
            <div style="text-align: center; padding: 50px; color: #999;">
                <p>Brak zamówień na ten dzień.</p>
            </div>
            @endforelse
        </div>
        
        {{-- Stopka --}}
        <div class="footer">
            <p>Dokument wygenerowany automatycznie przez System Cateringowy</p>
            <p>Zamówienia podlegają realizacji zgodnie z terminem złożenia</p>
        </div>
        
    </div>
</body>
</html>