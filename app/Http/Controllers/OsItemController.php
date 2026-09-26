<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use App\Models\OsItem;
use App\Models\Produto;
use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OsItemController extends Controller
{
    public function store(Request $request, OrdemServico $os)
    {
        $validado = $request->validate([
            'tipo'           => 'required|in:produto,servico',
            'produto_id'     => 'required_if:tipo,produto|nullable|exists:produtos,id',
            'servico_id'     => 'nullable|exists:servicos,id',
            'descricao'      => 'nullable|string|max:255',
            'quantidade'     => 'required|integer|min:1',
            'valor_unitario' => 'nullable|numeric|min:0',
        ]);

        // Validações extras ANTES de abrir a transação,
        // para poder redirecionar de volta corretamente em caso de erro
        if ($validado['tipo'] === 'produto') {
            $produto = Produto::find($validado['produto_id']);

            if ($validado['quantidade'] > $produto->estoque_atual) {
                return back()
                    ->withInput()
                    ->withErrors(['quantidade' => "Estoque insuficiente para {$produto->nome}. Disponível: {$produto->estoque_atual}."]);
            }
        }

        if ($validado['tipo'] === 'servico'
            && empty($validado['servico_id'])
            && empty($validado['descricao'])) {
            return back()
                ->withInput()
                ->withErrors(['descricao' => 'Selecione um serviço do catálogo ou escreva uma descrição.']);
        }

        DB::transaction(function () use ($validado, $os) {

            if ($validado['tipo'] === 'produto') {
                $produto = Produto::find($validado['produto_id']);
                $valor = $validado['valor_unitario'] ?? $produto->preco_venda;

                $os->itens()->create([
                    'produto_id'     => $produto->id,
                    'quantidade'     => $validado['quantidade'],
                    'valor_unitario' => $valor,
                ]);

                Produto::where('id', $produto->id)
                    ->decrement('estoque_atual', $validado['quantidade']);

            } else {
                $servico = !empty($validado['servico_id'])
                    ? Servico::find($validado['servico_id'])
                    : null;

                $valor = $validado['valor_unitario'] ?? $servico?->valor_padrao ?? 0;

                $os->itens()->create([
                    'servico_id'     => $servico?->id,
                    'descricao'      => $validado['descricao'] ?? null,
                    'quantidade'     => $validado['quantidade'],
                    'valor_unitario' => $valor,
                ]);
            }

            $os->recalcularTotal();
        });

        return redirect()->route('os.show', $os)
            ->with('sucesso', 'Item adicionado à Ordem de Serviço!');
    }

    public function destroy(OsItem $item)
    {
        $os = $item->ordemServico;

        DB::transaction(function () use ($item, $os) {
            if ($item->produto_id) {
                Produto::where('id', $item->produto_id)
                    ->increment('estoque_atual', $item->quantidade);
            }

            $item->delete();
            $os->recalcularTotal();
        });

        return redirect()->route('os.show', $os)
            ->with('sucesso', 'Item removido da Ordem de Serviço.');
    }
}