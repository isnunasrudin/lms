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
        Schema::table('bans', function (Blueprint $table) {
            $table->text('user_agent')->nullable()->change();
            $table->ipAddress()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bans', function (Blueprint $table) {
            $table->text('user_agent')->change();
            $table->ipAddress()->change();
        });
    }
};
