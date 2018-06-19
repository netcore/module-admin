<?php

namespace Modules\Admin\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Kalnoy\Nestedset\NodeTrait;
use Kalnoy\Nestedset\QueryBuilder;
use Nwidart\Modules\Facades\Module;
use Dimsav\Translatable\Translatable;

use Modules\Content\Models\Entry;
use Modules\Translate\Traits\SyncTranslations;
use Modules\Admin\Translations\MenuItemTranslation;

use Netcore\Translator\Helpers\TransHelper;

class MenuItem extends Model
{
    use Translatable, SyncTranslations, NodeTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'netcore_admin__menu_items';

    /**
     * The attributes that are mass assignable.
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
        'parameters',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'url',
        'active',
    ];

    /**
     * Translation model class.
     *
     * @var string
     */
    public $translationModel = MenuItemTranslation::class;

    /**
     * Attributes that are translatable.
     *
     * @var array
     */
    public $translatedAttributes = [
        'name',
        'value',
        'parameters',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'translations',
    ];

    /**
     * Menu item belongs to the menu,
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Attributes that should be scoped.
     *
     * @return array
     */
    protected function getScopeAttributes(): array
    {
        return ['menu_id'];
    }

    /** -------------------- Scopes -------------------- */

    /**
     * Scope only active menu items.
     *
     * @param $query
     * @return \Kalnoy\Nestedset\QueryBuilder
     */
    public function scopeActive(QueryBuilder $query): QueryBuilder
    {
        try {
            try {
                $modules = Module::allDisabled();
            } catch (Exception $exception) {
                $modules = Module::disabled();
            }
        } catch (Exception $exception) {
            $modules = [];
        }

        $disabledModules = array_keys($modules);

        return $query->whereNotIn('module', $disabledModules);
    }

    /** -------------------- Accessors -------------------- */

    /**
     * Get URL attribute.
     *
     * @return string
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
     * Active class attribute.
     *
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

    /** -------------------- Helper methods -------------------- */

    /**
     * Format item for response.
     *
     * @param $locale
     * @return array
     */
    public function formatResponse($locale): array
    {
        $item = $this;
        $translation = $item->translateOrNew($locale);
        $value = $translation->value;

        $apiUrl = null;

        if ($item->type == 'page') {
            $value = null;
            $entry = Entry::find($translation->value);

            if ($entry && $entry->key) {
                $value = '/' . Entry::find($translation->value)->slug;
                $apiUrl = route('api.content.get-page', $entry->key);
            }
        } elseif ($item->type == 'url') {
            $entry = Entry::whereHas('translations', function ($q) use ($value) {
                $q->where('slug', str_replace('/', '', $value));
            })->first();

            if ($entry && $entry->key) {
                $apiUrl = route('api.content.get-page', $entry->key);
            }
        }

        return [
            'id'              => $item->id,
            'icon'            => $item->icon,
            'type'            => $item->type,
            'target'          => $item->target,
            'active_resolver' => $item->active_resolver,
            'name'            => $translation->name,
            'value'           => $value,
            'api'             => $apiUrl,
        ];
    }
}
