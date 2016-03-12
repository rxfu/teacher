<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 单位
 *
 * @author FuRongxin
 * @date 2016-03-12
 * @version 2.0
 */
class Department extends Model {

	protected $table = 'xt_department';

	protected $primaryKey = 'dw';

	public $incrementing = false;

	public $timestatmps = false;

	/**
	 * 个人资料
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  object     所属对象
	 */
	public function profiles() {
		return $this->hasMany('App\Models\User', 'xy', 'dw');
	}

}
