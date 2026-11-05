<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->string('type')->default('actualite'); // actualite | evenement
            $t->text('excerpt')->nullable();
            $t->longText('body');
            $t->string('image')->nullable();          // ex: images/events/culte.jpg
            $t->dateTime('starts_at')->nullable();
            $t->string('location')->nullable();
            $t->boolean('is_published')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
