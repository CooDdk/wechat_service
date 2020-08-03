<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class WechatApp
 * @package App\Models
 */
class WechatApp extends Model
{
    const TABLE = 'sjd_wechat_app';

    protected $table = self::TABLE;

    protected $dateFormat = 'U';
}
