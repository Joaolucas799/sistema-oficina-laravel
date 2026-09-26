<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\MovimentacaoEstoque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaidaEstoqueController extends Controller
{
    public function index()
    {
        $saidas = MovimentacaoEstoque::with('produto')
            ->where('tipo', 'saida')
            ->latest()
            ->get();

        return view('estoque.saidas.index', compact('saidas'));
    }

    public function create()
    {
        $produtos = Produto::orderBy('nome')->get();

        return view('estoque.saidas.create', compact('produtos'));
    }

    public function store(Request $request)
    {
        $validado = $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'quantidade' => 'required|integer|min:1',
            'motivo'     => 'nullable|string|max:255',
            'e_venda'    => 'nullable|boolean',
        ]);

        $produto = Produto::find($validado['produto_id']);

        if ($validado['quantidade'] > $produto->estoque_atual) {
            return back()
                ->withInput()
                ->withErrors([
                    'quantidade' => "Estoque insuficiente. Disponível: {$produto->estoque_atual} unidade(s)."
                ]);
        }

        $eVenda = $request->boolean('e_venda');
        $valorVenda = $eVenda ? $produto->preco_venda * $validado['quantidade'] : null;

        DB::transaction(function () use ($validado, $eVenda, $valorVenda) {

            MovimentacaoEstoque::create([
                'produto_id'  => $validado['produto_id'],
                'tipo'        => 'saida',
                'quantidade'  => $validado['quantidade'],
                'motivo'      => $validado['motivo'] ?? ($eVenda ? 'Venda balcão' : 'Saída manual'),
                'user_id'     => auth()->id(),
                'e_venda'     => $eVenda,
                'valor_venda' => $valorVenda,
            ]);

            Produto::where('id', $validado['produto_id'])
                ->decrement('estoque_atual', $validado['quantidade']);
        });

        return redirect()->route('saidas.index')
            ->with('sucesso', 'Saída de estoque registrada com sucesso!');
    }
}