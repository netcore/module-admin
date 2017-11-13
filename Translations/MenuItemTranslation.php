<?php

namespace Modules\Admin\Translations;

use Illuminate\Database\Eloquent\Model;

class MenuItemTranslation extends Model
{
    protected $table = 'netcore_admin__menu_item_translations';

    protected $fillable = [
        'locale',
        'name'
    ];
}
