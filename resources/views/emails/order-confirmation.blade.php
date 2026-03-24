<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Potwierdzenie zamówienia posiłków</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #28a745;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .order-table th {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
        }
        .order-table td {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        .total {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .time-info {
            background-color: #e7f3ff;
            padding: 12px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Potwierdzenie zamówienia</h1>
            <p>System Cateringowy</p>
        </div>
        
        <div class="content">
            <h2>Szanowni Państwo,</h2>
            
            <p>Potwierdzamy otrzymanie zamówienia złożonego przez oddział <strong>{{ $ward->name }}</strong>.</p>
            
            <div class="time-info">
                <strong>📅 Data zamówienia:</strong> {{ $submittedAt->format('d.m.Y') }}<br>
                <strong>⏰ Godzina złożenia:</strong> {{ $submittedAt->format('H:i:s') }}
            </div>
            
            <h3>Szczegóły zamówienia:</h3>
            
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Dieta</th>
                        <th>Ilość</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->diet->name }}</td>
                        <td style="text-align: center;">{{ $order->quantity }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="total">
                        <td><strong>RAZEM</strong></td>
                        <td style="text-align: center;"><strong>{{ $orders->sum('quantity') }}</strong></td>
                    </tr>
                </tfoot>
            </table>
            
            <p style="margin-top: 20px;">
                W przypadku jakichkolwiek zmian prosimy o kontakt z działem żywienia 
                najpóźniej do godziny <strong>18:00</strong>.
            </p>
            
            <hr>
            
            <p style="font-size: 14px; color: #6c757d;">
                Dziękujemy za skorzystanie z naszego systemu.<br>
                Życzymy smacznego!
            </p>
        </div>
        
        <div class="footer">
            <p>System Cateringowy &copy; {{ date('Y') }}<br>
            Wiadomość wygenerowana automatycznie. Prosimy nie odpowiadać na ten e-mail.</p>
        </div>
    </div>
</body>
</html>