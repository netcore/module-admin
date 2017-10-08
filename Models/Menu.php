<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'netcore_admin__menu';

    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items() {
        return $this->hasMany(MenuItem::class);
    }
}
