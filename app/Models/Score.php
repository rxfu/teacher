<?php

namespace App\Models;

use App\Models\BaseModel as Model;

/**
 * 学生成绩
 *
 * @author FuRongxin
 * @date 2016-03-12
 * @version 2.0
 */
class Score extends Model {

	protected $table = 'cj_web';

	protected $primaryKey = 'kcxh';

	public $incrementing = false;

	public $timestamps = false;

	// 修复删除操作
	protected $secondaryKey = ['xh', 'nd', 'xq', 'kcxh'];

	/**
	 * 教学任务书
	 * @author FuRongxin
	 * @date    2016-03-13
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function task() {
		return $this->hasMany('App\Models\Task', 'kcxh', 'kcxh')
			->whereNd($this->nd)
			->whereXq($this->xq);
	}

	/**
	 * 考试状态
	 * @author FuRongxin
	 * @date    2016-03-13
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function status() {
		return $this->belongsTo('App\Models\Status', 'kszt', 'dm');
	}
}
