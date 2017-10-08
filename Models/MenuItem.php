<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

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
     * @return mixed|string
     */
    public function getUrlAttribute(){
        $url = 'javascript:;';

        if ($this->type == 'route' ){
            $url = route($this->value);
        } elseif($this->type == 'url'){
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
}
