<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 性别
 *
 * @author FuRongxin
 * @date 2016-03-12
 * @version 2.0
 */
class Gender extends Model {

	protected $table = 'zd_xb';

	protected $primaryKey = 'dm';

	public $incremting = false;

	public $timestamps = false;

	/**
	 * 个人资料
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function profiles() {
		return $this->hasMany('App\Models\User', 'xb', 'dm');
	}
}
