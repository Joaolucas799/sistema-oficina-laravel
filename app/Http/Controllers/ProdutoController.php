<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Categoria;
use App\Models\Fornecedor;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
       
        $produtos = Produto::with('categoria', 'fornecedor')
            ->orderBy('nome')
            ->get();

        return view('produtos.index', compact('produtos'));
    }

    public function create()
    {
        
        $categorias = Categoria::orderBy('nome')->get();
        $fornecedores = Fornecedor::orderBy('nome')->get();

        return view('produtos.create', compact('categorias', 'fornecedores'));
    }

    public function store(Request $request)
    {
        $validado = $request->validate([
            'nome'            => 'required|string|max:255',
            'codigo'          => 'required|string|max:50|unique:produtos,codigo',
            'categoria_id'    => 'nullable|exists:categorias,id',
            'fornecedor_id'   => 'nullable|exists:fornecedores,id',
            'unidade'         => 'required|string|max:10',
            'preco_custo'     => 'required|numeric|min:0',
            'preco_venda'     => 'required|numeric|min:0',
            'estoque_atual'   => 'required|integer|min:0',
            'estoque_minimo'  => 'required|integer|min:0',
        ]);

        Produto::create($validado);

        return redirect()->route('produtos.index')
            ->with('sucesso', 'Produto cadastrado com sucesso!');
    }

    public function edit(Produto $produto)
    {
        $categorias = Categoria::orderBy('nome')->get();
        $fornecedores = Fornecedor::orderBy('nome')->get();

        return view('produtos.edit', compact('produto', 'categorias', 'fornecedores'));
    }

    public function update(Request $request, Produto $produto)
    {
        $validado = $request->validate([
            'nome'            => 'required|string|max:255',
            'codigo'          => 'required|string|max:50|unique:produtos,codigo,' . $produto->id,
            'categoria_id'    => 'nullable|exists:categorias,id',
            'fornecedor_id'   => 'nullable|exists:fornecedores,id',
            'unidade'         => 'required|string|max:10',
            'preco_custo'     => 'required|numeric|min:0',
            'preco_venda'     => 'required|numeric|min:0',
            'estoque_atual'   => 'required|integer|min:0',
            'estoque_minimo'  => 'required|integer|min:0',
        ]);

        $produto->update($validado);

        return redirect()->route('produtos.index')
            ->with('sucesso', 'Produto atualizado com sucesso!');
    }

    public function destroy(Produto $produto)
    {
        $produto->delete();

        return redirect()->route('produtos.index')
            ->with('sucesso', 'Produto removido com sucesso!');
    }
}