<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', fn (Blueprint $table) => $table->timestamp('ends_at')->nullable()->change());
    }

    public function down(): void
    {
        if (DB::table('subscriptions')->whereNull('ends_at')->exists()) {
            throw new RuntimeException('Cannot make ends_at required while lifetime access records exist.');
        }

        Schema::table('subscriptions', fn (Blueprint $table) => $table->timestamp('ends_at')->nullable(false)->change());
    }
};
