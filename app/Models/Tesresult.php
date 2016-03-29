<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 教师评学结果
 *
 * @author FuRongxin
 * @date 2016-03-29
 * @version 2.0
 */
class Tesresult extends Model {

	protected $table = 'px_pfjg';

	protected $primaryKey = 'jsgh';

	public $incrementing = false;

	public $timestamps = false;

	/**
	 * 评价标准
	 * @author FuRongxin
	 * @date    2016-03-29
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function item() {
		return $this->belongsTo('App\Models\Tesitem', 'pjbz_id', 'id');
	}
}
