<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Nwidart\Modules\Facades\Module;

class MenuItem extends Model
{
    protected $table = 'netcore_admin__menu_items';


    protected $fillable = ['name','icon','type','value','module','is_active', 'active_resolver', 'parent_id', 'parameters'];

    protected $appends = ['url', 'active'];

    use NodeTrait;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu() {
        return $this->belongsTo(Menu::class);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        $modules = Module::disabled();
        $disabledModules = array_keys($modules);

        return $query->whereNotIn('module', $disabledModules);
    }

    /**
     * @return mixed|string
     */
    public function getUrlAttribute(){
        $url = 'javascript:;';

        if ($this->type == 'route' ){
            $url = route($this->value, (array) $this->parameters);
        } elseif($this->type == 'url'){
            $url = $this->value;
        } elseif($this->type == 'page'){
            $url = $this->value;
        }

        return $url;
    }

    /**
     * @return string
     */
    public function getActiveAttribute(){
        $active = '';

        if($this->type == 'route' ){
            $active = (active_class(
                if_route_pattern(
                    [$this->active_resolver ? $this->active_resolver : $this->value]
                )
            ));
        }

        return $active;
    }

    /**
     * @param $value
     * @return object
     */
    public function getParametersAttribute($value){
        return (object) json_decode($value);
    }
}
