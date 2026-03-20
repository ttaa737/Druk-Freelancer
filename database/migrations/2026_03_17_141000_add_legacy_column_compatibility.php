<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'verification_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            });

            DB::table('users')
                ->where('identity_verified', 1)
                ->update(['verification_status' => 'verified']);
        }

        if (Schema::hasTable('jobs')) {
            Schema::table('jobs', function (Blueprint $table) {
                if (!Schema::hasColumn('jobs', 'remote_ok')) {
                    $table->boolean('remote_ok')->default(true);
                }
                if (!Schema::hasColumn('jobs', 'is_featured')) {
                    $table->boolean('is_featured')->default(false);
                }
            });

            if (Schema::hasColumn('jobs', 'remote_allowed')) {
                DB::statement('UPDATE jobs SET remote_ok = remote_allowed WHERE remote_ok IS NULL OR remote_ok = 1');
            }
        }

        if (Schema::hasTable('proposals')) {
            Schema::table('proposals', function (Blueprint $table) {
                if (!Schema::hasColumn('proposals', 'is_shortlisted')) {
                    $table->boolean('is_shortlisted')->default(false);
                }
                if (!Schema::hasColumn('proposals', 'shortlisted_at')) {
                    $table->timestamp('shortlisted_at')->nullable();
                }
                if (!Schema::hasColumn('proposals', 'awarded_at')) {
                    $table->timestamp('awarded_at')->nullable();
                }
            });

            DB::table('proposals')
                ->where('status', 'shortlisted')
                ->update(['is_shortlisted' => 1]);

            DB::table('proposals')
                ->where('status', 'accepted')
                ->whereNull('awarded_at')
                ->update(['awarded_at' => now()]);
        }

        if (Schema::hasTable('conversations')) {
            Schema::table('conversations', function (Blueprint $table) {
                if (!Schema::hasColumn('conversations', 'poster_id')) {
                    $table->unsignedBigInteger('poster_id')->nullable();
                }
                if (!Schema::hasColumn('conversations', 'freelancer_id')) {
                    $table->unsignedBigInteger('freelancer_id')->nullable();
                }
                if (!Schema::hasColumn('conversations', 'poster_archived')) {
                    $table->boolean('poster_archived')->default(false);
                }
                if (!Schema::hasColumn('conversations', 'freelancer_archived')) {
                    $table->boolean('freelancer_archived')->default(false);
                }
            });

            if (Schema::hasColumn('conversations', 'participant_one')) {
                DB::statement('UPDATE conversations SET poster_id = COALESCE(poster_id, participant_one)');
            }
            if (Schema::hasColumn('conversations', 'participant_two')) {
                DB::statement('UPDATE conversations SET freelancer_id = COALESCE(freelancer_id, participant_two)');
            }
            if (Schema::hasColumn('conversations', 'is_archived_by_one')) {
                DB::statement('UPDATE conversations SET poster_archived = is_archived_by_one WHERE poster_archived = 0');
            }
            if (Schema::hasColumn('conversations', 'is_archived_by_two')) {
                DB::statement('UPDATE conversations SET freelancer_archived = is_archived_by_two WHERE freelancer_archived = 0');
            }
        }

        if (Schema::hasTable('audit_logs')) {
            Schema::table('audit_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('audit_logs', 'event')) {
                    $table->string('event')->nullable();
                }
                if (!Schema::hasColumn('audit_logs', 'auditable_type')) {
                    $table->string('auditable_type')->nullable();
                }
                if (!Schema::hasColumn('audit_logs', 'auditable_id')) {
                    $table->unsignedBigInteger('auditable_id')->nullable();
                }
                if (!Schema::hasColumn('audit_logs', 'url')) {
                    $table->string('url')->nullable();
                }
            });

            if (Schema::hasColumn('audit_logs', 'action')) {
                DB::statement('UPDATE audit_logs SET event = COALESCE(event, action)');
            }
            if (Schema::hasColumn('audit_logs', 'entity_type')) {
                DB::statement('UPDATE audit_logs SET auditable_type = COALESCE(auditable_type, entity_type)');
            }
            if (Schema::hasColumn('audit_logs', 'entity_id')) {
                DB::statement('UPDATE audit_logs SET auditable_id = COALESCE(auditable_id, entity_id)');
            }
            if (Schema::hasColumn('audit_logs', 'module')) {
                DB::statement('UPDATE audit_logs SET url = COALESCE(url, module)');
            }
        }
    }

    public function down(): void
    {
        // Compatibility migration intentionally keeps schema additive.
    }
};
