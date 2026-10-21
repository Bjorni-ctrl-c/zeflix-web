<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->string('role', 10)->default('user')->after('email'));

        Schema::create('films', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('slug')->unique();
            $t->text('synopsis');
            $t->string('poster_url', 500)->nullable();
            $t->string('genre', 80)->nullable();
            $t->unsignedSmallInteger('duration_minutes')->default(0);
            $t->string('access', 10)->default('premium');      // free | premium
            $t->string('status', 10)->default('draft');        // draft | published | hidden | archived
            $t->string('video_provider', 10)->default('youtube'); // youtube | mp4 | hls
            $t->string('video_ref', 500)->nullable();
            $t->text('rights_note')->nullable();
            $t->timestamp('published_at')->nullable();
            $t->timestamps();
        });

        Schema::create('transactions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('order_id')->unique();
            $t->unsignedInteger('amount');
            $t->string('status', 10)->default('pending'); // pending | paid | failed | expired
            $t->string('payment_type', 40)->nullable();
            $t->string('snap_token')->nullable();
            $t->string('redirect_url', 500)->nullable();
            $t->json('raw')->nullable();
            $t->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('transaction_id')->unique()->constrained()->cascadeOnDelete();
            $t->timestamp('starts_at');
            $t->timestamp('ends_at')->index();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('films');
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('role'));
    }
};
