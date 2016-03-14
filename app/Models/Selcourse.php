<?php

namespace App\Models;

use App\Models\BaseModel as Model;

/**
 * 选课信息
 *
 * @author FuRongxin
 * @date 2016-03-14
 * @version 2.0
 */
class Selcourse extends Model {

	protected $table = 'xk_xkxx';

	protected $primaryKey = 'xh';

	public $incrementing = false;

	public $timestamps = false;

	/**
	 * 学期
	 * @author FuRongxin
	 * @date    2016-03-14
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function term() {
		return $this->belongsTo('App\Models\Term', 'xq', 'dm');
	}

	/**
	 * 课程
	 * @author FuRongxin
	 * @date    2016-03-14
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function course() {
		return $this->belongsTo('App\Models\Course', 'kch', 'kch');
	}

	/**
	 * 排课表
	 * @author FuRongxin
	 * @date    2016-03-14
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function timetables() {
		return $this->hasMany('App\Models\Timetable', 'kcxh', 'kcxh')
			->whereNd(session('year'))
			->whereXq(session('term'));
	}

}
