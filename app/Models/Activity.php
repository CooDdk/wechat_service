<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    const TABLE = 'sjd_activities';

    const FEATURE_TYPE = [
        'form'  => 1,
        'group' => 2,
    ];

    const FEATURE_TYPE_TEXT = [
        1 => '报名',
        2 => '拼团',
    ];

    const PAY_TYPE = [
        'online' => 1,
    ];

    const CREATED_AT = 'create_time';

    const UPDATED_AT = 'update_time';

    const STATUS = [
        'valid'   => 1,
        'invalid' => 0,
    ];

    protected $table      = self::TABLE;
    protected $dateFormat = 'U';
}
