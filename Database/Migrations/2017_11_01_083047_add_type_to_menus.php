<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToMenus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('netcore_admin__menus', 'type')){
            Schema::table('netcore_admin__menus', function (Blueprint $table) {
                $table->string('type')->default('admin');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasColumn('netcore_admin__menus', 'type')){
            Schema::table('netcore_admin__menus', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
}
