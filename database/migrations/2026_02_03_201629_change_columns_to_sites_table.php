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
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn('is_active');
            $table
                ->boolean('enabled')
                ->default(true)
                ->after('domain');
            $table
                ->boolean('blocked')
                ->default(false)
                ->after('domain');
            $table
                ->string('block_reason')
                ->nullable()
                ->after('domain');
            $table
                ->timestamp('blocked_at')
                ->nullable()
                ->after('domain');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropColumn(['enabled', 'blocked', 'block_reason', 'blocked_at']);
            $table->boolean('is_active')->default(false);
        });
    }
};
