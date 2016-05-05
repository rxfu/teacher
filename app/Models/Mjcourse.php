<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * 专业课程信息
 *
 * @author FuRongxin
 * @date 2016-03-13
 * @version 2.0
 */
class Mjcourse extends Model {

	protected $table = 'pk_kczy';

	protected $primaryKey = 'kcxh';

	public $incrementing = false;

	public $timestamps = false;

	/**
	 * 学期
	 * @author FuRongxin
	 * @date    2016-03-27
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function term() {
		return $this->belongsTo('App\Models\Term', 'xq', 'dm');
	}

	/**
	 * 开课学院
	 * @author FuRongxin
	 * @date    2016-03-20
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function college() {
		return $this->belongsTo('App\Models\Department', 'kkxy', 'dw');
	}

	/**
	 * 教学任务书
	 * @author FuRongxin
	 * @date    2016-03-13
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function task() {
		return $this->belongsTo('App\Models\Task', 'kcxh', 'kcxh')
			->whereNd($this->nd)
			->whereXq($this->xq);
	}

	/**
	 * 课程表
	 * @author FuRongxin
	 * @date    2016-02-24
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function timetables() {
		return $this->hasMany('App\Models\Timetable', 'kcxh', 'kcxh')
			->orderBy('zc');
	}

	/**
	 * 教学计划
	 * @author FuRongxin
	 * @date    2016-03-24
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function plan() {
		return $this->belongsTo('App\Models\Plan', 'zy', 'zy')
			->whereNj($this->nj)
			->whereZsjj($this->zsjj)
			->whereKch(Str::substr($this->kcxh, 2, 8));
	}

	/**
	 * 专业
	 * @author FuRongxin
	 * @date    2016-05-05
	 * @version 2.1
	 * @return  object 所属对象
	 */
	public function major() {
		return $this->belongsTo('App\Models\Major', 'zy', 'zy');
	}

}
