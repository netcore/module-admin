<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('netcore_admin__menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->string('module')->nullable()->index();
            $table->string('icon')->nullable();
            $table->string('type')->nullable();
            $table->string('target')->nullable();
            $table->integer('menu_id')->unsigned();
            $table->foreign('menu_id')->references('id')->on('netcore_admin__menus')->onDelete('cascade');
            $table->string('active_resolver')->nullable();
            $table->boolean('is_active')->default(0);
            NestedSet::columns($table);
            $table->timestamps();
        });
        Schema::create('netcore_admin__menu_item_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_item_id')->unsigned();
            $table->foreign('menu_item_id')->references('id')->on('netcore_admin__menu_items')->onDelete('cascade');
            $table->string('locale')->index();
            $table->string('name');
            $table->string('value')->nullable();
            $table->text('parameters')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('netcore_admin__menu_item_translations');
        Schema::dropIfExists('netcore_admin__menu_items');
    }
}
