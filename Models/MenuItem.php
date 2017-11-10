<?php

namespace Modules\Admin\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Kalnoy\Nestedset\NodeTrait;
use Modules\Admin\Translations\MenuItemTranslation;
use Modules\Admin\Traits\SyncTranslations;
use Modules\Content\Models\Channel;
use Modules\Content\Models\Entry;
use Nwidart\Modules\Facades\Module;

class MenuItem extends Model
{
    //use Translatable, SyncTranslations, NodeTrait;
    use NodeTrait;

    protected $table = 'netcore_admin__menu_items';

    protected $fillable = ['name','icon','type','value','module','is_active', 'active_resolver', 'parent_id', 'parameters'];

    protected $appends = ['url', 'active'];

    /**
     * @var string
     */
    public $translationModel = MenuItemTranslation::class;

    /**
     * @var array
     */
    public $translatedAttributes = [
        'name',
    ];

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

            /**
             * Quick and dirty
             *
             * TODO should be changed to some global url getter method when module-content is ready
             */

            $url = url('/');

            $entry = Entry::whereIsActive(1)->find($this->value);
            if($entry){
                $entryTranslation = $entry->translations->first();
                $entrySlug = $entryTranslation ? $entryTranslation->slug : '';

                $url = url($entrySlug);

                if($entry->channel_id){
                    $channel = Channel::find($entry->channel_id);
                    if($channel){
                        $channelTranslation = $channel->translations->first();
                        $channelSlug = $channelTranslation ? $channelTranslation->slug : '';

                        $url = url($channelSlug.'/'.$entrySlug);
                    }
                }
            }
        }

        return $url;
    }

    /**
     * @return string
     */
    public function getActiveAttribute(){
        $active = '';

        if($this->type == 'route' ){
            $pattern = [$this->value];
            if($this->active_resolver){
                $pattern = array_map(function($item){
                    return trim($item);
                }, explode(',', $this->active_resolver));
            }

            $active = (active_class(
                if_route_pattern($pattern)
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
