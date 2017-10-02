<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class MenuItem extends Model
{
    protected $fillable = ['name','icon','type','value','module','is_active', 'active_resolver'];

    use NodeTrait;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu() {
        return $this->belongsTo(Menu::class);
    }
}
