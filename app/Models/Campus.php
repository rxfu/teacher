<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 校区
 *
 * @author FuRongxin
 * @date 2016-03-18
 * @version 2.0
 */
class Campus extends Model {

	protected $table = 'zd_xqh';

	protected $primaryKey = 'dm';

	public $incrementing = false;

	public $timestamps = false;

	/**
	 * 排课表
	 * @author FuRongxin
	 * @date    2016-03-18
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function timetables() {
		return $this->hasMany('App\Models\Timetable', 'xqh', 'dm');
	}
}
