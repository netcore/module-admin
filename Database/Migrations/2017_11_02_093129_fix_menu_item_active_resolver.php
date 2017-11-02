<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Modules\Admin\Models\MenuItem;

class FixMenuItemActiveResolver extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('netcore_admin__menu_items')){
            $menuItem = MenuItem::where('value', 'admin::menu.index')->whereNull('active_resolver')->first();
            if($menuItem){
                $menuItem->active_resolver = 'admin::menu.*';
                $menuItem->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('netcore_admin__menu_items')){
            $menuItem = MenuItem::where('value', 'admin::menu.index')->where('active_resolver', 'admin::menu.*')->first();
            if($menuItem){
                $menuItem->active_resolver = null;
                $menuItem->save();
            }
        }
    }
}
