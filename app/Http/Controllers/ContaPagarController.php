<?php

namespace App\Http\Controllers;

use App\Models\ContaPagar;
use App\Models\Fornecedor;
use Illuminate\Http\Request;

class ContaPagarController extends Controller
{
    public function index(Request $request)
    {
        $query = ContaPagar::with('fornecedor')->orderBy('data_vencimento');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $contas = $query->get();

        return view('contas-pagar.index', compact('contas'));
    }

    public function create()
    {
        $fornecedores = Fornecedor::orderBy('nome')->get();
        return view('contas-pagar.create', compact('fornecedores'));
    }

    public function store(Request $request)
    {
        $validado = $request->validate([
            'fornecedor_id'    => 'required|exists:fornecedores,id',
            'descricao'        => 'required|string|max:255',
            'valor_total'      => 'required|numeric|min:0.01',
            'data_vencimento'  => 'required|date',
        ]);

        ContaPagar::create($validado);

        return redirect()->route('contas-pagar.index')
            ->with('sucesso', 'Conta a pagar cadastrada com sucesso!');
    }

    public function edit(ContaPagar $contasPagar)
    {
        $fornecedores = Fornecedor::orderBy('nome')->get();
        return view('contas-pagar.edit', ['conta' => $contasPagar, 'fornecedores' => $fornecedores]);
    }

    public function update(Request $request, ContaPagar $contasPagar)
    {
        $validado = $request->validate([
            'fornecedor_id'    => 'required|exists:fornecedores,id',
            'descricao'        => 'required|string|max:255',
            'valor_total'      => 'required|numeric|min:0.01',
            'data_vencimento'  => 'required|date',
        ]);

        $contasPagar->update($validado);
        $contasPagar->atualizarStatus();

        return redirect()->route('contas-pagar.index')
            ->with('sucesso', 'Conta atualizada com sucesso!');
    }

    public function destroy(ContaPagar $contasPagar)
    {
        $contasPagar->delete();
        return redirect()->route('contas-pagar.index')
            ->with('sucesso', 'Conta excluída com sucesso!');
    }

    public function pagar(Request $request, ContaPagar $contasPagar)
    {
        $validado = $request->validate([
            'valor'          => "required|numeric|min:0.01|max:{$contasPagar->valor_restante}",
            'data_pagamento' => 'required|date',
        ]);

        $contasPagar->pagamentos()->create($validado);

        $contasPagar->valor_pago += $validado['valor'];
        $contasPagar->atualizarStatus();

        return redirect()->route('contas-pagar.index')
            ->with('sucesso', 'Pagamento registrado com sucesso!');
    }
}