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
        'value',
        'parameters'
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @param $value
     * @return object
     */
    public function getParametersAttribute($value)
    {
        return (object)json_decode($value);
    }


    public function setParametersAttribute($value)
    {
        if(!$value){
            $this->attributes['parameters'] = json_encode([]);
        } else {
            if(is_string($value)){
                $this->attributes['parameters'] = $value;
            } elseif(is_array($value)){
                $this->attributes['parameters'] = json_encode($value);
            }
        }
    }
}
