<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParametersToMenuItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('netcore_admin__menu_items', function (Blueprint $table) {
            $table->text('parameters')->after('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('netcore_admin__menu_items', function (Blueprint $table) {
            $table->dropColumn('parameters');
        });
    }
}
