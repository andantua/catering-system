<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WardController extends Controller
{
    /**
     * Lista oddziałów
     */
    public function index()
    {
        $wards = Ward::orderBy('name')->get();
        return view('admin-panel.wards.index', compact('wards'));
    }

    /**
     * Formularz dodawania oddziału
     */
    public function create()
    {
        return view('admin-panel.wards.create');
    }

    /**
     * Zapisz nowy oddział
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:wards,email',
            'contact_person' => 'nullable|string|max:255',
            'password' => 'required|string|min:4',
        ]);

        Ward::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact_person' => $request->contact_person,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.wards.index')
            ->with('success', 'Oddział został dodany.');
    }

    /**
     * Formularz edycji oddziału
     */
    public function edit($id)
    {
        $ward = Ward::findOrFail($id);
        return view('admin-panel.wards.edit', compact('ward'));
    }

    /**
     * Aktualizuj oddział
     */
    public function update(Request $request, $id)
    {
        $ward = Ward::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:wards,email,' . $id,
            'contact_person' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:4',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'contact_person' => $request->contact_person,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $ward->update($data);

        return redirect()->route('admin.wards.index')
            ->with('success', 'Oddział został zaktualizowany.');
    }

    /**
     * Usuń oddział
     */
    public function destroy($id)
    {
        $ward = Ward::findOrFail($id);
        
        // Sprawdź czy oddział ma zamówienia
        if ($ward->orders()->count() > 0) {
            return back()->with('error', 'Nie można usunąć oddziału, który ma zamówienia.');
        }
        
        $ward->delete();
        
        return redirect()->route('admin.wards.index')
            ->with('success', 'Oddział został usunięty.');
    }
}