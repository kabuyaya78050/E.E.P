<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('events', function (Blueprint $t) {
            $t->string('departure_time', 5)->nullable()->after('location');   // ex: 06:30
            $t->string('departure_place')->nullable()->after('departure_time'); // lieu de rassemblement
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $t) {
            $t->dropColumn(['departure_time', 'departure_place']);
        });
    }
};
