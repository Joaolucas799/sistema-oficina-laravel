<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contas_pagar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fornecedor_id')->constrained('fornecedores')->cascadeOnDelete();
            $table->foreignId('entrada_estoque_id')->nullable()->constrained('movimentacoes_estoque')->nullOnDelete();
            $table->string('descricao');
            $table->decimal('valor_total', 10, 2);
            $table->decimal('valor_pago', 10, 2)->default(0);
            $table->date('data_vencimento');
            $table->enum('status', ['pendente', 'parcial', 'pago'])->default('pendente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contas_pagar');
    }
};