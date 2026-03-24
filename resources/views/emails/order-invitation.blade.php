<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zaproszenie do zamówienia posiłków</title>
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
        .code-box {
            background-color: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 5px;
            color: #28a745;
            font-family: monospace;
        }
        .button {
            display: inline-block;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background-color: #218838;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px;
            margin: 20px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🍽️ System Cateringowy</h1>
            <p>Zamówienie posiłków</p>
        </div>
        
        <div class="content">
            <h2>Witaj {{ $ward->name }}!</h2>
            
            <p>Otrzymujesz to zaproszenie, ponieważ zbliża się termin składania zamówień na posiłki.</p>
            
            <div class="code-box">
                <p><strong>Twój kod weryfikacyjny:</strong></p>
                <div class="code">{{ $code }}</div>
                <p style="margin-top: 10px; font-size: 14px;">Kod jest ważny przez 24 godziny.</p>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ route('order.code.form', $token) }}" class="button">
                    📝 ZŁÓŻ ZAMÓWIENIE
                </a>
            </div>
            
            <p>lub skopiuj link do przeglądarki:</p>
            <p style="word-break: break-all; font-size: 12px; color: #6c757d;">
                {{ route('order.code.form', $token) }}
            </p>
            
            <div class="warning">
                <strong>⚠️ Ważne:</strong>
                <ul style="margin: 10px 0 0 20px;">
                    <li>Zamówienia przyjmowane są do godziny <strong>18:00</strong></li>
                    <li>Po tym czasie formularz zostanie automatycznie zablokowany</li>
                    <li>Potwierdzenie zamówienia otrzymasz na ten adres e-mail</li>
                </ul>
            </div>
        </div>
        
        <div class="footer">
            <p>System Cateringowy &copy; {{ date('Y') }}<br>
            Wiadomość wygenerowana automatycznie. Prosimy nie odpowiadać na ten e-mail.</p>
        </div>
    </div>
</body>
</html>