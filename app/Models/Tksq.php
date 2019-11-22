<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 调停课申请表
 *
 * @author FuRongxin
 * @date 2019-11-22
 * @version 2.3
 */
class Tksq extends Model {

	protected $table = 'pk_tksq';

	protected $primaryKey = 'id';

	public $incrementing = false;

	public $timestamps = false;

	/**
	 * 申请原因
	 * @author FuRongxin
	 * @date    2019-11-22
	 * @version 2.3
	 * @return  object 所属对象
	 */
	public function sqyy() {
		return $this->hasMany('App\Models\Tksqyy', 'sqyy', 'dm');
	}

	/**
	 * 学期
	 * @author FuRongxin
	 * @date    2019-11-22
	 * @version 2.3
	 * @return  object 所属对象
	 */
	public function term() {
		return $this->hasMany('App\Models\Term', 'xq', 'dm');
	}
}
