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
        DB::table('users')->where('status', 'active')->update(['status' => 'y']);
        DB::table('users')->where('status', '!=', 'y')->update(['status' => 'n']);

        Schema::table('users', function (Blueprint $table) {
            //
            $table->char('status', 1)->default('y')->comment("'y' for Active, 'n' for Inactive")->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('status', 255)->default('active')->comment('')->change();

            DB::table('users')->where('status', 'y')->update(['status' => 'active']);
            DB::table('users')->where('status', 'n')->update(['status' => 'inactive']); 
        });
    }
};
