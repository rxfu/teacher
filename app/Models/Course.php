<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 课程信息
 *
 * @author FuRongxin
 * @date 2016-03-12
 * @version 2.0
 */
class Course extends Model {

	protected $table = 'jx_kc';

	protected $primaryKey = 'kch';

	public $incrementing = false;

	public $timestamps = false;

	protected $casts = [
		'zt' => 'boolean',
	];

	/**
	 * 教学任务
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  object 所属对象
	 */
	public function tasks() {
		return $this->hasMany('App\Models\Task', 'kch', 'kch');
	}
}
