<!DOCTYPE html>
<html lang="pl">
<head><meta charset="UTF-8"><title>Zamówienie - {{ $ward->name }} - {{ $orderDate->format('d.m.Y') }}</title>
<script src="https://cdn.tailwindcss.com"></script>
<style>@media print{.no-print{display:none}}</style>
</head>
<body class="p-8">
<div class="max-w-2xl mx-auto">
<div class="no-print text-center mb-6"><button onclick="window.print()" class="bg-indigo-600 text-white px-4 py-2 rounded">🖨️ Drukuj</button> <button onclick="window.close()" class="bg-gray-500 text-white px-4 py-2 rounded ml-2">Zamknij</button></div>
<div class="text-center mb-6"><h1 class="text-2xl font-bold">📋 Zestawienie zamówienia</h1><p class="text-gray-600">System Cateringowy</p></div>
<div class="mb-6"><p><strong>Oddział:</strong> {{ $ward->name }}</p><p><strong>E-mail:</strong> {{ $ward->email }}</p><p><strong>Data zamówienia:</strong> {{ $orderDate->format('d.m.Y') }}</p></div>
@if($orders->count())
<table class="w-full border"><thead class="bg-gray-100"><tr><th class="border p-2 text-left">Dieta</th><th class="border p-2 text-right">Ilość</th></tr></thead><tbody>@foreach($orders as $order)<tr><td class="border p-2">{{ $order->diet->name }}</td><td class="border p-2 text-right">{{ $order->quantity }}</td></tr>@endforeach<tr class="bg-gray-100 font-bold"><td class="border p-2">RAZEM</td><td class="border p-2 text-right">{{ $total }}</td></tr></tbody></table>
@else<p class="text-center text-gray-500">Brak zamówień</p>@endif
<div class="text-center text-gray-400 text-sm mt-8">Wygenerowano: {{ now()->format('d.m.Y H:i') }}</div>
</div>
</body>
</html>