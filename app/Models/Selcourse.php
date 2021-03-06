<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

	/**
	 * 学生成绩
	 * @author FuRongxin
	 * @date    2016-03-20
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function score() {
		return $this->belongsTo('App\Models\Score', 'kcxh', 'kcxh');
	}

	/**
	 * 学生信息
	 * @author FuRongxin
	 * @date    2016-12-02
	 * @version 2.1.3
	 * @return  object 所属对象
	 */
	public function student() {
		return $this->belongsTo('App\Models\Student', 'xh', 'xh');
	}
}
