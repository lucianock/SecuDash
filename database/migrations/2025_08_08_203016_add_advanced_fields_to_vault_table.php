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
        Schema::table('vault', function (Blueprint $table) {
            $table->json('tags')->nullable()->after('notes');
            $table->boolean('is_favorite')->default(false)->after('tags');
            $table->boolean('auto_login')->default(false)->after('is_favorite');
            $table->timestamp('expires_at')->nullable()->after('auto_login');
            $table->enum('security_level', ['low', 'medium', 'high', 'critical'])->default('medium')->after('expires_at');
            $table->timestamp('last_accessed')->nullable()->after('security_level');
            $table->integer('access_count')->default(0)->after('last_accessed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vault', function (Blueprint $table) {
            $table->dropColumn([
                'tags',
                'is_favorite',
                'auto_login',
                'expires_at',
                'security_level',
                'last_accessed',
                'access_count'
            ]);
        });
    }
};
