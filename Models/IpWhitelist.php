<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class IpWhitelist extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'netcore_admin__ip_whitelist';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = [
        'ip',
        'type',
        'comment'
    ];
}
