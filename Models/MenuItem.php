<?php

namespace Modules\Admin\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use Modules\Admin\Translations\MenuItemTranslation;
use Modules\Translate\Traits\SyncTranslations;
use Netcore\Translator\Helpers\TransHelper;
use Nwidart\Modules\Facades\Module;

class MenuItem extends Model
{
    use Translatable, SyncTranslations, NodeTrait;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'netcore_admin__menu_items';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'icon',
        'type',
        'value',
        'module',
        'is_active',
        'active_resolver',
        'parent_id',
        'parameters'
    ];

    /**
     * @var array
     */
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
        'value',
        'parameters'
    ];

    /**
     * @var array
     */
    protected $with = ['translations'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * @return array
     */
    protected function getScopeAttributes()
    {
        return ['menu_id'];
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        $modules = Module::allDisabled();
        $disabledModules = array_keys($modules);

        return $query->whereNotIn('module', $disabledModules);
    }

    /**
     * @return mixed|string
     */
    public function getUrlAttribute()
    {
        $url = 'javascript:;';

        $value = trans_model($this, TransHelper::getLanguage(), 'value');
        if (!$value) {
            $value = trans_model($this, TransHelper::getFallbackLanguage(), 'value');
        }

        if ($this->type == 'route') {
            $url = route($value, (array)$this->parameters);
        } elseif ($this->type == 'url') {
            $url = url($value);
        } elseif ($this->type == 'page') {
            $url = content()->getUrl($value, true);
        }

        return $url;
    }

    /**
     * @return string
     */
    public function getActiveAttribute()
    {
        $active = '';

        if ($this->type == 'route') {
            $pattern = [$this->value];
            if ($this->active_resolver) {
                $pattern = array_map(function ($item) {
                    return trim($item);
                }, explode(',', $this->active_resolver));
            }

            $active = (active_class(if_route_pattern($pattern)));
        }

        return $active;
    }

    /**
     * @param $locale
     * @return array
     */
    public function formatResponse($locale)
    {
        $item = $this;

        $translation = $item->translateOrNew($locale);

        return [
            'id'              => $item->id,
            'icon'            => $item->icon,
            'type'            => $item->type,
            'target'          => $item->target,
            'active_resolver' => $item->active_resolver,
            'name'            => $translation->name,
            'value'           => $translation->value
        ];
    }
}
