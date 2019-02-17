<?php

namespace App\Models;

/**
 * 大创项目评审意见
 *
 * @author FuRongxin
 * @date 2019-02-18
 * @version 2.3
 */
class Dcxmps extends DcxmModel {

	protected $table = 'dc_xmps';

	protected $primaryKey = 'id';

	public $timestamps = false;

	/**
	 * 项目信息
	 * @author FuRongxin
	 * @date    2019-02-18
	 * @version 2.3
	 * @return  object 所属对象
	 */
	public function project() {
		return $this->belongsTo('App\Models\Dcxmxx', 'xm_id', 'id');
	}
}
