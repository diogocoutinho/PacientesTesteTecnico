<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patients_addresses', function (Blueprint $table) {
            $table->id();
            $table->integer('patient_id');
            $table->string('postal_code');
            $table->string('street');
            $table->integer('number')->nullable();
            $table->string('neighborhood');
            $table->string('city');
            $table->string('state');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['patient_id', 'postal_code', 'street', 'number', 'neighborhood', 'city', 'state']);
            $table->foreign('patient_id')->references('id')->on('patients');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients_addresses');
    }
};
