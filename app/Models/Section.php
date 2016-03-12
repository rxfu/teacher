<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 教研室
 *
 * @author FuRongxin
 * @date 2016-03-12
 * @version 2.0
 */
class Section extends Model {

	protected $table = 'zd_jys';

	protected $primaryKey = 'dm';

	public $incrmenting = false;

	public $timestamps = false;

	/**
	 * 个人资料
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  object     所属对象
	 */
	public function profiles() {
		return $this->hasMany('App\Models\User', 'jys', 'dm');
	}
}
