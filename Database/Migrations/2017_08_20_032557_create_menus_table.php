<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('netcore_admin__menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key')->unique();
            $table->string('type')->default('admin');
            $table->timestamps();
        });

        Schema::create('netcore_admin__menu_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_id')->unsigned();
            $table->foreign('menu_id')->references('id')->on('netcore_admin__menus')->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('netcore_admin__menu_translations');
        Schema::dropIfExists('netcore_admin__menus');
    }
}
