<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('fiados', function (Blueprint $table) {
            $table->foreignId('ordem_servico_id')->nullable()->after('cliente_id')->constrained('ordens_servico')->nullOnDelete();
        });
    }
    public function down(): void
    {
        Schema::table('fiados', function (Blueprint $table) {
            $table->dropForeign(['ordem_servico_id']);
            $table->dropColumn('ordem_servico_id');
        });
    }
};
