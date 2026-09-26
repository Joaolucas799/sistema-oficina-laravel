<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\Http\Request;

class ServicoController extends Controller
{
    public function index()
    {
        $servicos = Servico::orderBy('nome')->get();
        return view('servicos.index', compact('servicos'));
    }

    public function create()
    {
        return view('servicos.create');
    }

    public function store(Request $request)
    {
        $validado = $request->validate([
            'nome'         => 'required|string|max:255',
            'valor_padrao' => 'required|numeric|min:0',
        ]);

        Servico::create($validado);

        return redirect()->route('servicos.index')
            ->with('sucesso', 'Serviço cadastrado com sucesso!');
    }

    public function edit(Servico $servico)
    {
        return view('servicos.edit', compact('servico'));
    }

    public function update(Request $request, Servico $servico)
    {
        $validado = $request->validate([
            'nome'         => 'required|string|max:255',
            'valor_padrao' => 'required|numeric|min:0',
        ]);

        $servico->update($validado);

        return redirect()->route('servicos.index')
            ->with('sucesso', 'Serviço atualizado com sucesso!');
    }

    public function destroy(Servico $servico)
    {
        $servico->delete();

        return redirect()->route('servicos.index')
            ->with('sucesso', 'Serviço removido com sucesso!');
    }
}