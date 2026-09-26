<?php

namespace App\Http\Controllers;

use App\Models\Fornecedor;
use Illuminate\Http\Request;

class FornecedorController extends Controller
{
    public function index()
    {
        $fornecedores = Fornecedor::orderBy('nome')->get();

        return view('fornecedores.index', compact('fornecedores'));
    }

    public function create()
    {
        return view('fornecedores.create');
    }

    public function store(Request $request)
    {
        $validado = $request->validate([
            'nome'       => 'required|string|max:255',
            'cnpj'       => 'nullable|string|max:20|unique:fornecedores,cnpj',
            'telefone'   => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:255',
            'observacao' => 'nullable|string|max:255',
        ]);

        Fornecedor::create($validado);

        return redirect()->route('fornecedores.index')
            ->with('sucesso', 'Fornecedor cadastrado com sucesso!');
    }

    public function edit(Fornecedor $fornecedor)
    {
        return view('fornecedores.edit', compact('fornecedor'));
    }

    public function update(Request $request, Fornecedor $fornecedor)
    {
        $validado = $request->validate([
            'nome'       => 'required|string|max:255',
            'cnpj'       => 'nullable|string|max:20|unique:fornecedores,cnpj,' . $fornecedor->id,
            'telefone'   => 'nullable|string|max:20',
            'email'      => 'nullable|email|max:255',
            'observacao' => 'nullable|string|max:255',
        ]);

        $fornecedor->update($validado);

        return redirect()->route('fornecedores.index')
            ->with('sucesso', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy(Fornecedor $fornecedor)
    {
        $fornecedor->delete();

        return redirect()->route('fornecedores.index')
            ->with('sucesso', 'Fornecedor removido com sucesso!');
    }
}