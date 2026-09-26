<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('nome')->get();

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $validado = $request->validate([
            'nome'      => 'required|string|max:255',
            'cpf_cnpj'  => 'nullable|string|max:20|unique:clientes,cpf_cnpj',
            'telefone'  => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:255',
            'endereco'  => 'nullable|string|max:255',
        ]);

        Cliente::create($validado);

        return redirect()->route('clientes.index')
            ->with('sucesso', 'Cliente cadastrado com sucesso!');
    }

    
    public function show(Cliente $cliente)
    {
        
        $cliente->load('veiculos');

        return view('clientes.show', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $validado = $request->validate([
            'nome'      => 'required|string|max:255',
            'cpf_cnpj'  => 'nullable|string|max:20|unique:clientes,cpf_cnpj,' . $cliente->id,
            'telefone'  => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:255',
            'endereco'  => 'nullable|string|max:255',
        ]);

        $cliente->update($validado);

        return redirect()->route('clientes.index')
            ->with('sucesso', 'Cliente atualizado com sucesso!');
    }

    public function destroy(Cliente $cliente)
    {
        
        $cliente->delete();

        return redirect()->route('clientes.index')
            ->with('sucesso', 'Cliente removido com sucesso!');
    }
}
