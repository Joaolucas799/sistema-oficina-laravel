<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('os_itens')) {
            Schema::create('os_itens', function (Blueprint $table) {
                $table->id();
                $table->foreignId('ordem_servico_id')->constrained('ordens_servico')->cascadeOnDelete();
                $table->foreignId('produto_id')->nullable()->constrained('produtos')->nullOnDelete();
                $table->foreignId('servico_id')->nullable()->constrained('servicos')->nullOnDelete();
                $table->string('descricao')->nullable();
                $table->integer('quantidade')->default(1);
                $table->decimal('valor_unitario', 10, 2)->default(0);
                $table->timestamps();
            });
        } elseif (!Schema::hasColumn('os_itens', 'descricao')) {
            Schema::table('os_itens', function (Blueprint $table) {
                $table->string('descricao')->nullable()->after('servico_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('os_itens');
    }
};