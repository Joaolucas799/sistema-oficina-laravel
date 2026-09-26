<?php

namespace App\Http\Controllers;

use App\Models\OrdemServico;
use App\Models\Cliente;
use Illuminate\Http\Request;

class OrdemServicoController extends Controller
{
    public function index()
    {
        $ordens = OrdemServico::with('cliente', 'veiculo')
            ->latest()
            ->get();

        return view('os.index', compact('ordens'));
    }

    public function create()
    {
        $clientes = Cliente::with('veiculos')->orderBy('nome')->get();

        return view('os.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $validado = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'veiculo_id' => 'required|exists:veiculos,id',
            'observacoes' => 'nullable|string',
        ]);

        $os = OrdemServico::create($validado);

        return redirect()->route('os.show', $os)
            ->with('sucesso', 'Ordem de Serviço aberta! Agora adicione as peças e serviços.');
    }

    public function show(OrdemServico $os)
    {
        $os->load('cliente', 'veiculo', 'itens.produto', 'itens.servico');

        $produtos = \App\Models\Produto::orderBy('nome')->get();
        $servicos = \App\Models\Servico::orderBy('nome')->get();

        return view('os.show', compact('os', 'produtos', 'servicos'));
    }

    public function update(Request $request, OrdemServico $os)
    {
        $validado = $request->validate([
            'status' => 'required|in:aberta,em_andamento,concluida,cancelada',
            'observacoes' => 'nullable|string',
        ]);

        if ($validado['status'] === 'concluida' && !$os->data_conclusao) {
            $validado['data_conclusao'] = now();
        }

        $os->update($validado);

        return redirect()->route('os.show', $os)
            ->with('sucesso', 'Ordem de Serviço atualizada!');
    }

    public function destroy(OrdemServico $os)
    {
        $os->delete();

        return redirect()->route('os.index')
            ->with('sucesso', 'Ordem de Serviço removida.');
    }

    public function imprimir(OrdemServico $os)
    {
        $os->load('cliente', 'veiculo', 'itens.produto', 'itens.servico');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('os.pdf', compact('os'));

        return $pdf->stream("os-{$os->id}.pdf");
    }
}