<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vault;
use Illuminate\Support\Facades\Auth; // Asegúrate de importar Auth

class VaultController extends Controller
{
    public function index()
    {
        $vaults = Vault::where('user_id', auth()->id())->get();
        return view('vault.index', compact('vaults'));
    }

    public function create()
    {
        return view('vault.create');
    }
    // VaultController.php
    public function store(Request $request)
    {
        // Validar los datos
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'host' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // Asignar el user_id automáticamente
        $validated['user_id'] = Auth::id(); // Usando Auth::id() en lugar de auth()->id()

        // Crear el nuevo registro
        Vault::create($validated);

        // Redirigir con mensaje de éxito
        return redirect()->route('vault.index')->with('success', 'Access added successfully!');
    }


    public function destroy(Vault $vault)
    {
        $this->authorize('delete', $vault);
        $vault->delete();

        return redirect()->route('vault.index')->with('success', 'Cuenta eliminada.');
    }
}
