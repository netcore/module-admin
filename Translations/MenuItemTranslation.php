<?php

namespace Modules\Admin\Translations;

use Illuminate\Database\Eloquent\Model;

class MenuItemTranslation extends Model
{
    /**
     * @var string
     */
    protected $table = 'netcore_admin__menu_item_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'locale',
        'name',
        'value'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
