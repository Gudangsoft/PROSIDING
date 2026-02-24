<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Conferences: blind review, deadline lock, page builder, OJS, meeting
        Schema::table('conferences', function (Blueprint $table) {
            $table->boolean('blind_review')->default(false)->after('is_active');
            $table->boolean('submission_locked')->default(false)->after('blind_review');
            $table->json('page_builder_blocks')->nullable()->after('submission_locked');
            $table->string('ojs_url', 500)->nullable()->after('page_builder_blocks');
            $table->string('ojs_api_key', 500)->nullable()->after('ojs_url');
            $table->string('ojs_journal_id')->nullable()->after('ojs_api_key');
            $table->string('doi_prefix')->nullable()->after('ojs_journal_id');
            $table->string('meeting_default_platform')->nullable()->after('doi_prefix');
            $table->string('meeting_default_link', 1000)->nullable()->after('meeting_default_platform');
            $table->json('submission_extra_fields')->nullable()->after('meeting_default_link');
        });

        // Papers: DOI, OJS, meeting, similarity
        Schema::table('papers', function (Blueprint $table) {
            $table->string('doi')->nullable()->after('article_link');
            $table->string('ojs_submission_id')->nullable()->after('doi');
            $table->string('ojs_status')->nullable()->after('ojs_submission_id');
            $table->timestamp('ojs_submitted_at')->nullable()->after('ojs_status');
            $table->string('meeting_link', 1000)->nullable()->after('ojs_submitted_at');
            $table->string('meeting_platform')->nullable()->after('meeting_link');
            $table->timestamp('meeting_scheduled_at')->nullable()->after('meeting_platform');
            $table->json('extra_field_values')->nullable()->after('meeting_scheduled_at');
            $table->decimal('similarity_cross_score', 5, 2)->nullable()->after('similarity_score');
        });
    }

    public function down(): void
    {
        Schema::table('conferences', function (Blueprint $table) {
            $table->dropColumn([
                'blind_review', 'submission_locked', 'page_builder_blocks',
                'ojs_url', 'ojs_api_key', 'ojs_journal_id', 'doi_prefix',
                'meeting_default_platform', 'meeting_default_link', 'submission_extra_fields',
            ]);
        });

        Schema::table('papers', function (Blueprint $table) {
            $table->dropColumn([
                'doi', 'ojs_submission_id', 'ojs_status', 'ojs_submitted_at',
                'meeting_link', 'meeting_platform', 'meeting_scheduled_at',
                'extra_field_values', 'similarity_cross_score',
            ]);
        });
    }
};
