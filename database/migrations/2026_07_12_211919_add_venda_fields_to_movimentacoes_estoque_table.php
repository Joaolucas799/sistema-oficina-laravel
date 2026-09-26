<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('movimentacoes_estoque', function (Blueprint $table) {
            $table->boolean('e_venda')->default(false)->after('motivo');
            $table->decimal('valor_venda', 10, 2)->nullable()->after('e_venda');
        });
    }

    public function down(): void
    {
        Schema::table('movimentacoes_estoque', function (Blueprint $table) {
            $table->dropColumn(['e_venda', 'valor_venda']);
        });
    }
};