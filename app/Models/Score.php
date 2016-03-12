<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 学生成绩
 *
 * @author FuRongxin
 * @date 2016-03-12
 * @version 2.0
 */
class Score extends Model {

	protected $table = 'cj_web';

	protected $primaryKey = 'xh';

	public $incrementing = false;

	public $timestamps = false;
}
