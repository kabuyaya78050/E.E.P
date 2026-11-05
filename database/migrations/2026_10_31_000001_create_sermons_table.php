<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sermons', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('preacher')->nullable();
            $t->string('scripture')->nullable();
            $t->date('preached_at');
            $t->string('video_url', 500);          // lien YouTube ou Facebook
            $t->text('description')->nullable();
            $t->boolean('is_published')->default(true);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sermons');
    }
};
