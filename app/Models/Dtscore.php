<?php

namespace App\Models;

use App\Models\Task;
use Illuminate\Database\Eloquent\Model;

/**
 * 学生过程成绩
 *
 * @author FuRongxin
 * @date 2016-03-18
 * @version 2.0
 */
class Dtscore extends Model {

	protected $table = 'cj_lscj';

	protected $primaryKey = 'xh';

	public $incrementing = false;

	public $timestamps = false;

	/**
	 * 学期
	 * @author FuRongxin
	 * @date    2016-03-18
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function term() {
		return $this->belongsTo('App\Models\Term', 'xq', 'dm');
	}

	/**
	 * 课程平台
	 * @author FuRongxin
	 * @date    2016-03-18
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function platform() {
		return $this->belongsTo('App\Models\Platform', 'kcpt', 'dm');
	}

	/**
	 * 课程性质
	 * @author FuRongxin
	 * @date    2016-03-18
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function property() {
		return $this->belongsTo('App\Models\Property', 'kcxz', 'dm');
	}

	/**
	 * 考核方式
	 * @author FuRongxin
	 * @date    2016-03-18
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function mode() {
		return $this->belongsTo('App\Models\Mode', 'kh', 'dm');
	}

	/**
	 * 考试状态
	 * @author FuRongxin
	 * @date    2016-03-18
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function status() {
		return $this->belongsTo('App\Models\Status', 'kszt', 'dm');
	}

	/**
	 * 教学任务书
	 * @author FuRongxin
	 * @date    2016-03-18
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function task() {
		return $this->belongsTo('App\Models\Task', 'kcxh', 'kcxh')
			->whereNd($this->nd)
			->whereXq($this->xq);
	}

}
