<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 教学任务书
 *
 * @author FuRongxin
 * @date 2016-03-12
 * @version 2.0
 */
class Task extends Model {

	protected $table = 'pk_jxrw';

	protected $primaryKey = 'jsgh';

	public $incrementing = false;

	public $timestamps = false;

	/**
	 * 学期
	 * @author FuRongxin
	 * @date    2016-03-17
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function term() {
		return $this->belongsTo('App\Models\Term', 'xq', 'dm');
	}

	/**
	 * 学生成绩
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function scores() {
		return $this->hasMany('App\Models\Score', 'kcxh', 'kcxh')
			->whereNd($this->nd)
			->whereXq($this->xq);
	}

	/**
	 * 课程
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function course() {
		return $this->belongsTo('App\Models\Course', 'kch', 'kch');
	}

	/**
	 * 成绩比例
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function ratio() {
		return $this->belongsTo('App\Models\Ratio', 'cjfs', 'fs');
	}

}
