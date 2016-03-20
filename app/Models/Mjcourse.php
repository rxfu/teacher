<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
	 * 课程平台
	 * @author FuRongxin
	 * @date    2016-03-13
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function platform() {
		return $this->belongsTo('App\Models\Platform', 'pt', 'dm');
	}

	/**
	 * 课程性质
	 * @author FuRongxin
	 * @date    2016-03-13
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function property() {
		return $this->belongsTo('App\Models\Property', 'xz', 'dm');
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

}
