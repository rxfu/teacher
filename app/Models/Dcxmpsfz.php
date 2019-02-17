<?php

namespace App\Models;

/**
 * 大创项目评审分组
 *
 * @author FuRongxin
 * @date 2019-02-18
 * @version 2.3
 */
class Dcxmpsfz extends DcxmModel {

	protected $table = 'dc_zjpsfz';

	public $timestamps = false;

	public $incrementing = false;

	public function group() {
		return $this->belongsTo('App\Models\Dcxmpsz', 'psz_id', 'id');
	}
}
