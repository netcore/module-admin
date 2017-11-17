<?php

namespace Modules\Admin\Translations;

use Illuminate\Database\Eloquent\Model;

class MenuTranslation extends Model
{
    /**
     * @var string
     */
    protected $table = 'netcore_admin__menu_translations';

    /**
     * @var array
     */
    protected $fillable = [
        'locale',
        'name'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;
}
