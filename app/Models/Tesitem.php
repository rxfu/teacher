<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 教师评学评价标准
 *
 * @author FuRongxin
 * @date 2016-03-29
 * @version 2.0
 */
class Tesitem extends Model {

	protected $table = 'px_pjbz';

	protected $casts = [
		'zt' => 'boolean',
	];

	/**
	 * 评价指标
	 * @author FuRongxin
	 * @date    2016-03-29
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function category() {
		return $this->belongsTo('App\Models\Tescategory', 'pjzb_id', 'id');
	}

	/**
	 * 评学结果
	 * @author FuRongxin
	 * @date    2016-03-29
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function results() {
		return $this->belongsTo('App\Models\Tesresult', 'pjbz_id', 'id');
	}
}
