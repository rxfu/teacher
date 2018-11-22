<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 学生信息
 *
 * @author FuRongxin
 * @date 2016-12-02
 * @version 2.1.3
 */
class Student extends Model {

	protected $table = 'xs_zxs';

	protected $primaryKey = 'xh';

	public $incrementing = false;

	public $timestamps = false;

	protected $connection = 'pgsql';

	/**
	 * 专业
	 * @author FuRongxin
	 * @date    2016-12-02
	 * @version 2.1.3
	 * @return  object 所属对象
	 */
	public function major() {
		return $this->belongsTo('App\Models\Major', 'zy', 'zy');
	}

	/**
	 * 学院
	 * @author FuRongxin
	 * @date    2016-01-12
	 * @version 2.0
	 * @return  object     所属对象
	 */
	public function college() {
		return $this->belongsTo('App\Models\Department', 'xy', 'dw');
	}

}
