<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Veiculo;
use Illuminate\Http\Request;

class VeiculoController extends Controller
{
    // Adiciona um veículo a um cliente específico
    public function store(Request $request, Cliente $cliente)
    {
        $validado = $request->validate([
            'placa'  => 'required|string|max:10|unique:veiculos,placa',
            'marca'  => 'required|string|max:100',
            'modelo' => 'required|string|max:100',
            'ano'    => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
        ]);

        // Cria o veículo já vinculado a esse cliente,
        // usando o relacionamento em vez de passar cliente_id manualmente
        $cliente->veiculos()->create($validado);

        return redirect()->route('clientes.show', $cliente)
            ->with('sucesso', 'Veículo adicionado com sucesso!');
    }

    // Remove um veículo específico
    public function destroy(Veiculo $veiculo)
    {
        $cliente = $veiculo->cliente;
        $veiculo->delete();

        return redirect()->route('clientes.show', $cliente)
            ->with('sucesso', 'Veículo removido com sucesso!');
    }
}