<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 国籍
 *
 * @author FuRongxin
 * @date 2016-03-12
 * @version 2.0
 */
class Country extends Model {

	protected $table = 'zd_gj';

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
		return $this->hasMany('App\Models\User', 'gj', 'dm');
	}
}
