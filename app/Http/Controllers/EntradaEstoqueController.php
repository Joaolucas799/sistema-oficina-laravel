<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Fornecedor;
use App\Models\MovimentacaoEstoque;
use App\Models\ContaPagar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntradaEstoqueController extends Controller
{
    public function index()
    {
        $entradas = MovimentacaoEstoque::with(['produto', 'fornecedor'])
            ->where('tipo', 'entrada')
            ->latest()
            ->get();

        return view('estoque.entradas.index', compact('entradas'));
    }

    public function create()
    {
        $produtos = Produto::orderBy('nome')->get();
        $fornecedores = Fornecedor::orderBy('nome')->get();

        return view('estoque.entradas.create', compact('produtos', 'fornecedores'));
    }

    public function store(Request $request)
    {
        $validado = $request->validate([
            'produto_id'     => 'required|exists:produtos,id',
            'quantidade'     => 'required|integer|min:1',
            'motivo'         => 'nullable|string|max:255',
            'fornecedor_id'  => 'nullable|exists:fornecedores,id',
            'valor_unitario' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($validado) {

            $entrada = MovimentacaoEstoque::create([
                'produto_id'     => $validado['produto_id'],
                'tipo'           => 'entrada',
                'quantidade'     => $validado['quantidade'],
                'motivo'         => $validado['motivo'] ?? 'Entrada manual',
                'user_id'        => auth()->id(),
                'fornecedor_id'  => $validado['fornecedor_id'] ?? null,
                'valor_unitario' => $validado['valor_unitario'] ?? null,
            ]);

            Produto::where('id', $validado['produto_id'])
                ->increment('estoque_atual', $validado['quantidade']);

            if (!empty($validado['fornecedor_id']) && !empty($validado['valor_unitario'])) {
                ContaPagar::create([
                    'fornecedor_id'      => $validado['fornecedor_id'],
                    'entrada_estoque_id' => $entrada->id,
                    'descricao'          => 'Entrada de estoque #' . $entrada->id . ' - ' . ($entrada->produto->nome ?? ''),
                    'valor_total'        => $validado['valor_unitario'],
                    'data_vencimento'    => now()->addDays(30),
                ]);
            }
        });

        return redirect()->route('entradas.index')
            ->with('sucesso', 'Entrada de estoque registrada com sucesso!');
    }
}