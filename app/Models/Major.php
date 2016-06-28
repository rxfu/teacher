<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 主修专业
 *
 * @author FuRongxin
 * @date 2016-05-05
 * @version 2.1
 */
class Major extends Model {

	protected $table = 'jx_zy';

	protected $primaryKey = 'zy';

	public $incrementing = false;

	public $timestamps = false;

	public function mjcourses() {
		return $this->hasMany('App\Models\Mjcourse', 'zy', 'zy');
	}

	public function college() {
		return $this->belongsTo('App\Models\Department', 'xy', 'dw');
	}
}
