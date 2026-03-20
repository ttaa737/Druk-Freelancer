<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'notification_preferences')) {
                    $table->json('notification_preferences')->nullable()->after('preferred_language');
                }
                if (!Schema::hasColumn('users', 'privacy_settings')) {
                    $table->json('privacy_settings')->nullable()->after('notification_preferences');
                }
            });
        }

        if (Schema::hasTable('proposals') && !Schema::hasTable('proposal_milestones')) {
            Schema::create('proposal_milestones', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('proposal_id');
                $table->string('title');
                $table->text('description')->nullable();
                $table->decimal('amount', 12, 2);
                $table->integer('duration_days');
                $table->integer('sort_order')->default(0);
                $table->timestamps();

                $table->foreign('proposal_id')->references('id')->on('proposals')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('jobs') && !Schema::hasTable('job_attachments')) {
            Schema::create('job_attachments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('job_id');
                $table->string('file_path');
                $table->string('original_name');
                $table->string('file_type')->nullable();
                $table->unsignedBigInteger('file_size')->nullable();
                $table->timestamps();

                $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('milestones') && !Schema::hasTable('milestone_attachments')) {
            Schema::create('milestone_attachments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('milestone_id');
                $table->string('file_path');
                $table->string('original_name');
                $table->string('file_type')->nullable();
                $table->unsignedBigInteger('file_size')->nullable();
                $table->enum('uploaded_by_role', ['freelancer', 'poster'])->default('freelancer');
                $table->timestamps();

                $table->foreign('milestone_id')->references('id')->on('milestones')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('dispute_cases') && !Schema::hasTable('dispute_comments')) {
            Schema::create('dispute_comments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('dispute_id');
                $table->unsignedBigInteger('user_id');
                $table->text('comment');
                $table->boolean('is_admin_note')->default(false);
                $table->timestamps();

                $table->foreign('dispute_id')->references('id')->on('dispute_cases')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (Schema::hasTable('users') && !Schema::hasTable('otps')) {
            Schema::create('otps', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('identifier');
                $table->enum('type', ['email_verify', 'phone_verify', 'login', 'withdrawal', 'password_reset']);
                $table->string('code', 10);
                $table->boolean('is_used')->default(false);
                $table->integer('attempts')->default(0);
                $table->timestamp('expires_at');
                $table->timestamp('used_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['identifier', 'type']);
            });
        }

        if (!Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id')->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('sessions')) {
            Schema::drop('sessions');
        }
        if (Schema::hasTable('otps')) {
            Schema::drop('otps');
        }
        if (Schema::hasTable('dispute_comments')) {
            Schema::drop('dispute_comments');
        }
        if (Schema::hasTable('milestone_attachments')) {
            Schema::drop('milestone_attachments');
        }
        if (Schema::hasTable('job_attachments')) {
            Schema::drop('job_attachments');
        }
        if (Schema::hasTable('proposal_milestones')) {
            Schema::drop('proposal_milestones');
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'privacy_settings')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'privacy_settings')) {
                    $table->dropColumn('privacy_settings');
                }
                if (Schema::hasColumn('users', 'notification_preferences')) {
                    $table->dropColumn('notification_preferences');
                }
            });
        }
    }
};
