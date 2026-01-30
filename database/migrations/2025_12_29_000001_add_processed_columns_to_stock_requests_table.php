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
        Schema::table('stock_requests', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('stock_requests', 'processed_by')) {
                $table->foreignId('processed_by')->nullable()->after('approved_by')
                    ->constrained('users')->nullOnDelete();
            }
            
            if (!Schema::hasColumn('stock_requests', 'processed_at')) {
                $table->timestamp('processed_at')->nullable()->after('approved_at');
            }
            
            if (!Schema::hasColumn('stock_requests', 'approval_notes')) {
                $table->text('approval_notes')->nullable()->after('notes');
            }
            
            if (!Schema::hasColumn('stock_requests', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('approval_notes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_requests', function (Blueprint $table) {
            if (Schema::hasColumn('stock_requests', 'processed_by')) {
                $table->dropForeign(['processed_by']);
                $table->dropColumn('processed_by');
            }
            
            if (Schema::hasColumn('stock_requests', 'processed_at')) {
                $table->dropColumn('processed_at');
            }
            
            if (Schema::hasColumn('stock_requests', 'approval_notes')) {
                $table->dropColumn('approval_notes');
            }
            
            if (Schema::hasColumn('stock_requests', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
        });
    }
};