<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 教师评学评价指标
 *
 * @author FuRongxin
 * @date 2016-03-29
 * @version 2.0
 */
class Tescategory extends Model {

	protected $table = 'px_pjzb';

	/**
	 * 评价标准
	 * @author FuRongxin
	 * @date    2016-03-29
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function item() {
		return $this->belongsTo('App\Models\Tesitem', 'pjzb_id', 'id');
	}
}
