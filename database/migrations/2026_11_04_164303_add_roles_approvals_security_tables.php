<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 30)->default('center_manager')->after('email'); // center_manager, topic_leader, moe_approver, moe_admin
            $table->string('designation')->nullable()->after('role');
            $table->string('phone')->nullable()->after('designation');
            $table->boolean('can_view')->default(true)->after('phone');
            $table->boolean('can_edit')->default(false)->after('can_view');
            $table->boolean('can_delete')->default(false)->after('can_edit');
            $table->boolean('can_approve')->default(false)->after('can_delete');
            $table->boolean('is_active')->default(true)->after('can_approve');
            $table->timestamp('last_login_at')->nullable()->after('remember_token');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
        });

        Schema::create('approval_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')->constrained('users')->cascadeOnDelete();
            $table->string('action_type', 50); // delete_lecturer, clear_week, clear_centre, reset_all, edit_setting
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('payload')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('decision_notes')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();
        });

        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('event', 50); // login_success, login_failed, lockout, logout, delete_requested, approval_granted, etc.
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_logs');
        Schema::dropIfExists('approval_requests');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'designation',
                'phone',
                'can_view',
                'can_edit',
                'can_delete',
                'can_approve',
                'is_active',
                'last_login_at',
                'last_login_ip',
            ]);
        });
    }
};
