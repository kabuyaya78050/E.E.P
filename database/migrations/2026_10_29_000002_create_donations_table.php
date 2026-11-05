<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $t) {
            $t->id();
            $t->string('reference')->unique();
            $t->string('name');
            $t->string('email')->nullable();
            $t->string('phone');
            $t->decimal('amount', 12, 2);
            $t->string('currency', 3);
            $t->string('purpose');
            $t->string('status')->default('pending'); // pending | paid | failed
            $t->string('order_number')->nullable();
            $t->json('gateway_response')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
