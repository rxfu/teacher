<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 职称
 *
 * @author FuRongxin
 * @date 2016-03-12
 * @version 2.0
 */
class Position extends Model {

	protected $table = 'zd_zc';

	protected $primaryKey = 'dm';

	public $incrementing = false;

	public $timestamps = false;

	protected $connection = 'pgsql';

	/**
	 * 个人资料
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function profiles() {
		return $this->hasMany('App\Models\User', 'zc', 'dm');
	}
}
