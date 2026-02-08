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
        Schema::table('sites', callback: function (Blueprint $table) {
            $table->string('telegram_channel', 50)->after('domain');
            $table->unique(['telegram_channel']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sites', function (Blueprint $table) {
            $table->dropUnique(['telegram_channel']);
            $table->dropColumn('telegram_channel');
        });
    }
};
