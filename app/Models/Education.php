<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 学历
 *
 * @author FuRongxin
 * @date 2016-03-12
 * @version 2.0
 */
class Education extends Model {

	protected $table = 'zd_xl';

	protected $primaryKey = 'dm';

	public $incrementing = false;

	public $timestamps = false;

	/**
	 * 个人资料
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function profiles() {
		return $this->hasMany('App\Models\User', 'xl', 'dm');
	}
}
