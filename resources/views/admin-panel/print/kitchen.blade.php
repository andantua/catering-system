<!DOCTYPE html>
<html lang="pl">
<head><meta charset="UTF-8"><title>Zestawienie zamówień - {{ $orderDate->format('d.m.Y') }}</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>@media print{.no-print{display:none}}</style>
</head>
<body class="p-8 bg-white">
<div class="max-w-5xl mx-auto">
<div class="no-print text-center mb-6"><button onclick="window.print()" class="bg-indigo-600 text-white px-4 py-2 rounded">🖨️ Drukuj</button> <button onclick="window.close()" class="bg-gray-500 text-white px-4 py-2 rounded ml-2">Zamknij</button></div>
<div class="text-center mb-8"><h1 class="text-2xl font-bold">🏥 ZESTAWIENIE ZAMÓWIEŃ</h1><p class="text-gray-600">Dla kuchni – {{ $orderDate->format('d.m.Y') }}</p></div>
<div class="grid grid-cols-3 gap-4 mb-6 bg-gray-100 p-4 rounded"><div><strong>Łącznie posiłków:</strong> {{ $totalAll }}</div><div><strong>Oddziały z zamówieniem:</strong> {{ $wardsSubmitted }} / {{ $totalWards }}</div><div><strong>Średnia:</strong> {{ $wardsSubmitted > 0 ? round($totalAll / $wardsSubmitted) : 0 }}</div></div>
<h2 class="text-xl font-bold mb-3">📊 Zestawienie diet</h2>
<table class="w-full border mb-6"><thead class="bg-gray-100"><tr><th class="border p-2 text-left">Dieta</th><th class="border p-2 text-right">Ilość</th></tr></thead><tbody>@foreach($summaryByDiet as $diet => $qty)<tr><td class="border p-2">{{ $diet }}</td><td class="border p-2 text-right">{{ $qty }}</td></tr>@endforeach<tr class="bg-gray-100 font-bold"><td class="border p-2">SUMA</td><td class="border p-2 text-right">{{ $totalAll }}</td></tr></tbody></table>
<h2 class="text-xl font-bold mb-3">🏥 Oddziały</h2>
@forelse($wardsSummary as $wardName => $data)
<div class="border rounded mb-4"><div class="bg-gray-100 p-3 font-bold flex justify-between"><span>{{ $wardName }}</span><span class="bg-indigo-600 text-white px-2 py-1 rounded text-sm">łącznie: {{ $data['total'] }}</span></div><div class="p-3"><table class="w-full"><tbody>@foreach($data['details'] as $diet => $qty)<tr><td class="py-1">{{ $diet }}</td><td class="text-right">{{ $qty }}</td></tr>@endforeach</tbody></table></div></div>
@empty<p class="text-center text-gray-500">Brak zamówień</p>@endforelse
<div class="text-center text-gray-400 text-sm mt-8">Wygenerowano: {{ now()->format('d.m.Y H:i') }}</div>
</div>
</body>
</html>