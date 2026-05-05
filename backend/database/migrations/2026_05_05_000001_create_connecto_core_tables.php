<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->enum('role', ['interviewee', 'interviewer', 'admin'])->default('interviewee');
            $table->string('auth_provider')->default('email');
            $table->enum('status', ['pending', 'active', 'suspended'])->default('active');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->text('bio')->nullable();
            $table->string('experience')->nullable();
            $table->json('skills')->nullable();
            $table->string('resume_url')->nullable();
            $table->string('target_role')->nullable();
            $table->decimal('pricing', 8, 2)->nullable();
            $table->json('availability')->nullable();
            $table->decimal('rating_average', 3, 2)->default(0);
            $table->unsignedInteger('review_count')->default(0);
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interviewer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('interviewee_id')->constrained('users')->cascadeOnDelete();
            $table->dateTime('scheduled_at');
            $table->unsignedSmallInteger('duration_minutes')->default(45);
            $table->enum('status', ['requested', 'accepted', 'rejected', 'paid', 'in_session', 'completed', 'cancelled', 'disputed'])->default('requested');
            $table->decimal('price', 8, 2);
            $table->string('video_room_id')->nullable();
            $table->json('chat_transcript')->nullable();
            $table->text('feedback')->nullable();
            $table->enum('dispute_status', ['none', 'open', 'resolved'])->default('none');
            $table->timestamps();
            $table->index(['interviewer_id', 'scheduled_at']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('amount', 8, 2);
            $table->decimal('commission', 8, 2);
            $table->decimal('interviewer_payout', 8, 2);
            $table->enum('status', ['requires_payment', 'held_in_escrow', 'released', 'refunded', 'failed'])->default('requires_payment');
            $table->string('stripe_payment_intent_id')->nullable()->index();
            $table->timestamp('released_at')->nullable();
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('review')->nullable();
            $table->timestamps();
        });

        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('balance', 10, 2)->default(0);
            $table->json('transactions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallets');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('users');
    }
};
