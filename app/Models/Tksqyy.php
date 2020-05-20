<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 调停课申请原因
 *
 * @author FuRongxin
 * @date 2019-11-22
 * @version 2.3
 */
class Tksqyy extends Model {

	protected $table = 'pk_sqyy';

	protected $primaryKey = 'dm';

	public $incrementing = false;

	public $timestamps = false;

	/**
	 * 调停课申请表
	 * @author FuRongxin
	 * @date    2019-11-22
	 * @version 2.3
	 * @return  object 所属对象
	 */
	public function tksqs() {
		return $this->hasMany('App\Models\Tksq', 'sqyy', 'dm');
	}
}
