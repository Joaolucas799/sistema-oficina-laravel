<?php
namespace App\Http\Controllers;
use App\Models\Fiado;
use App\Models\FiadoPagamento;
use App\Models\Cliente;
use App\Models\OrdemServico;
use App\Models\Produto;
use Illuminate\Http\Request;
class FiadoController extends Controller
{
    public function index()
    {
        $fiados = Fiado::with(['cliente', 'pagamentos', 'itens', 'ordemServico'])
            ->orderBy('created_at', 'desc')
            ->get();
        $clientes = Cliente::orderBy('nome')->get();
        $ordensServico = OrdemServico::with('cliente')
            ->orderBy('created_at', 'desc')
            ->get();
        $produtos = Produto::orderBy("nome")->get();
        return view('fiado.index', compact('fiados', 'clientes', 'ordensServico', 'produtos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'ordem_servico_id' => 'nullable|exists:ordens_servico,id',
            'origem' => 'nullable|string',
            'itens' => 'required_without:ordem_servico_id|array|min:1',
            'itens.*.descricao' => 'required_with:itens|string',
            'itens.*.valor' => 'required_with:itens|numeric|min:0.01',
        ]);

        $itensParaCriar = [];
        $origem = $request->origem;

        if ($request->ordem_servico_id) {
            $os = OrdemServico::with('itens')->findOrFail($request->ordem_servico_id);
            foreach ($os->itens as $item) {
                $itensParaCriar[] = [
                    'descricao' => $item->nomeItem(),
                    'valor' => $item->quantidade * $item->valor_unitario,
                ];
            }
            if (!$origem) {
                $origem = 'Ordem de Serviço #' . $os->id;
            }
        } else {
            $itensParaCriar = $request->itens;
        }

        $valorTotal = array_sum(array_column($itensParaCriar, 'valor'));

        if ($valorTotal <= 0) {
            return redirect()->back()->withErrors(['itens' => 'Informe pelo menos um item com valor.']);
        }

        $fiado = Fiado::create([
            'cliente_id' => $request->cliente_id,
            'ordem_servico_id' => $request->ordem_servico_id,
            'valor_total' => $valorTotal,
            'descricao' => null,
            'origem' => $origem,
            'status' => 'pendente',
            'data' => now(),
        ]);

        foreach ($itensParaCriar as $item) {
            $fiado->itens()->create([
                'descricao' => $item['descricao'],
                'valor' => $item['valor'],
            ]);
        }

        return redirect()->route('fiado.index')->with('success', 'Fiado registrado com sucesso.');
    }

    public function osJson(OrdemServico $os)
    {
        $os->load('itens');
        $itens = $os->itens->map(function ($item) {
            return [
                'descricao' => $item->nomeItem(),
                'valor' => $item->quantidade * $item->valor_unitario,
            ];
        });
        return response()->json([
            'cliente_id' => $os->cliente_id,
            'itens' => $itens,
            'total' => $itens->sum('valor'),
        ]);
    }

    public function show(Fiado $fiado)
    {
        $fiado->load('pagamentos', 'cliente', 'itens', 'ordemServico');
        return view('fiado.show', compact('fiado'));
    }

    public function pagar(Request $request, Fiado $fiado)
    {
        $request->validate([
            'valor' => 'required|numeric|min:0.01',
        ]);
        FiadoPagamento::create([
            'fiado_id' => $fiado->id,
            'valor' => $request->valor,
            'data_pagamento' => now(),
        ]);
        $totalPago = $fiado->pagamentos()->sum('valor') + $request->valor;
        if ($totalPago >= $fiado->valor_total) {
            $fiado->status = 'pago';
        } else {
            $fiado->status = 'parcial';
        }
        $fiado->save();
        return redirect()->back()->with('success', 'Pagamento registrado com sucesso.');
    }

    public function destroy(Fiado $fiado)
    {
        $fiado->itens()->delete();
        $fiado->pagamentos()->delete();
        $fiado->delete();
        return redirect()->route('fiado.index')->with('success', 'Fiado excluído com sucesso.');
    }

    public function porCliente(Cliente $cliente)
    {
        $fiados = Fiado::with('pagamentos', 'itens')
            ->where('cliente_id', $cliente->id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('fiado.cliente', compact('fiados', 'cliente'));
    }
}
