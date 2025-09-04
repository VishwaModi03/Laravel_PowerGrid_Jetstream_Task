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
            //
            $table->foreignId('role_id')->nullable()->constrained('roles')->nullOnDelete()->after('email');
        });
        Schema::table('users',function(Blueprint $table){
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->nullable()->after('email');
        });
        
        // Optional: backfill users.role from roles.name via role_id
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('role_id'); // or dropForeign + dropColumn depending on Laravel version
        });
    }
};
