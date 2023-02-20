<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->boolean('on_home_page')->default(false);
            $table->integer('sorting')->default('999');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {

        if (app()->isLocal()) {
            Schema::table('brands', function (Blueprint $table) {
                $table->dropColumn('on_home_page');
                $table->dropColumn('sorting');
            });
        }
    }
};
