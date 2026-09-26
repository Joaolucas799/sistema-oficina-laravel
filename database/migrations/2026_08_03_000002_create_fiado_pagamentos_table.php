<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fiado_pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fiado_id')->constrained('fiados')->cascadeOnDelete();
            $table->decimal('valor', 10, 2);
            $table->date('data_pagamento')->useCurrent();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('fiado_pagamentos');
    }
};
