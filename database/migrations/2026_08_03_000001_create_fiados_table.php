<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->string('origem')->nullable();
            $table->string('descricao')->nullable();
            $table->decimal('valor_total', 10, 2);
            $table->enum('status', ['pendente', 'parcial', 'pago'])->default('pendente');
            $table->date('data')->useCurrent();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('fiados');
    }
};
