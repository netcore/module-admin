<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class IpWhitelist extends Model
{

    /**
     * @var string
     */
    protected $table = 'netcore_admin__ip_whitelist';

    /**
     * @var array
     */
    protected $fillable = [
        'ip',
        'type',
        'comment'
    ];
}
