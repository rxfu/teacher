<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 课程平台
 *
 * @author FuRongxin
 * @date 2016-12-04
 * @version 2.1.3
 */
class Platform extends Model {

	protected $table = 'zd_pt';

	protected $primaryKey = 'dm';

	public $incrementing = false;

	public $timestamps = false;

	/**
	 * 教学计划
	 * @author FuRongxin
	 * @date    2016-12-04
	 * @version 2.1.3
	 * @return  object 所属对象
	 */
	public function plans() {
		return $this->hasMany('App\Models\Plan', 'pt', 'dm');
	}

}
