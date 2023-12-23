<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('mother_name');
            $table->date('birthday');
            $table->string('cpf')->unique();
            $table->string('cns')->unique();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['first_name', 'last_name', 'mother_name', 'birthday', 'cpf', 'cns']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
