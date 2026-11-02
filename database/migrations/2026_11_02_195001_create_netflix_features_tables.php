<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Additional metadata columns for films
        Schema::table('films', function (Blueprint $t) {
            $t->unsignedTinyInteger('match_rate')->default(95)->after('genre');
            $t->string('age_rating', 10)->default('13+')->after('match_rate'); // SU | 13+ | 16+ | 18+
            $t->string('quality', 10)->default('4K')->after('age_rating');     // HD | 4K | Ultra HD
            $t->boolean('is_featured')->default(false)->after('quality');
            $t->unsignedInteger('views_count')->default(0)->after('is_featured');
        });

        // Watchlist (Daftar Saya)
        Schema::create('watchlists', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('film_id')->constrained()->cascadeOnDelete();
            $t->timestamps();

            $t->unique(['user_id', 'film_id']);
        });

        // Watch Progress (Lanjutkan Menonton)
        Schema::create('watch_progresses', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('film_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('progress_percent')->default(0); // 0 - 100
            $t->unsignedInteger('seconds')->default(0);
            $t->unsignedInteger('duration_seconds')->default(0);
            $t->timestamp('last_watched_at')->useCurrent();
            $t->timestamps();

            $t->unique(['user_id', 'film_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('watch_progresses');
        Schema::dropIfExists('watchlists');

        Schema::table('films', function (Blueprint $t) {
            $t->dropColumn(['match_rate', 'age_rating', 'quality', 'is_featured', 'views_count']);
        });
    }
};
