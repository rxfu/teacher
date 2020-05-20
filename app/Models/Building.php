<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 教学楼
 *
 * @author FuRongxin
 * @date 2019-11-25
 * @version 2.0
 */
class Building extends Model {

	protected $table = 'zd_jxl';

	protected $primaryKey = 'dm';

	public $incrementing = false;

	public $timestamps = false;
}
