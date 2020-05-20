<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 校历
 *
 * @author FuRongxin
 * @date 2019-11-26
 * @version 2.3
 */
class Calendar extends Model {

	protected $table = 'jx_xl';

	protected $primaryKey = 'rq';

	public $incrementing = false;

	public $timestamps = false;

	/**
	 * 学期
	 * @author FuRongxin
	 * @date    2019-11-26
	 * @version 2.3
	 * @return  object 所属对象
	 */
	public function term() {
		return $this->belongsTo('App\Models\Term', 'xq', 'dm');
	}

}
