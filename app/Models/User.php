<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'jsgh', 'mm', 'zt',
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [
		'mm', 'zt',
	];

	protected $table = 'pk_js';

	public $incrementing = false;

	public $timestamps = false;

	protected $primaryKey = 'jsgh';

	protected $casts = [
		'zt' => 'boolean',
	];

	protected $connection = 'pgsql';

	/**
	 * 获取密码
	 * @author FuRongxin
	 * @date    2016-03-11
	 * @version 2.0
	 * @return  string 密码
	 */
	public function getAuthPassword() {
		return $this->mm;
	}

	/**
	 * 获取remember token
	 * @author FuRongxin
	 * @date    2016-03-11
	 * @version 2.0
	 * @return  null
	 */
	public function getRememberToken() {
		return null;
	}

	/**
	 * 设置remember token
	 * @author FuRongxin
	 * @date    2016-03-11
	 * @version 2.0
	 * @param   string $value token值
	 */
	public function setRememberToken($value) {

	}

	/**
	 * 获取remember token名
	 * @author FuRongxin
	 * @date    2016-03-11
	 * @version 2.0
	 * @return  null
	 */
	public function getRememberTokenName() {
		return null;
	}

	/**
	 * 覆盖原方法，忽略remember token
	 * @author FuRongxin
	 * @date    2016-03-11
	 * @version 2.0
	 */
	public function setAttribute($key, $value) {
		if ($key != $this->getRememberTokenName()) {
			parent::setAttribute($key, $value);
		}
	}

	/**
	 * 学院
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  object     所属对象
	 */
	public function college() {
		return $this->belongsTo('App\Models\Department', 'xy', 'dw');
	}

	/**
	 * 性别
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  object     所属对象
	 */
	public function gender() {
		return $this->belongsTo('App\Models\Gender', 'xbdm', 'dm');
	}

	/**
	 * 证件类型
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  object     所属对象
	 */
	public function idtype() {
		return $this->belongsTo('App\Models\Idtype', 'zjlx', 'dm');
	}

	/**
	 * 国籍
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  object     所属对象
	 */
	public function country() {
		return $this->belongsTo('App\Models\Country', 'gj', 'dm');
	}

	/**
	 * 职称
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  object     所属对象
	 */
	public function position() {
		return $this->belongsTo('App\Models\Position', 'zc', 'dm');
	}

	/**
	 * 学历
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  object     所属对象
	 */
	public function education() {
		return $this->belongsTo('App\Models\Education', 'xl', 'dm');
	}

	/**
	 * 学位
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  object     所属对象
	 */
	public function degree() {
		return $this->belongsTo('App\Models\Degree', 'xw', 'dm');
	}

	/**
	 * 系所
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  object     所属对象
	 */
	public function school() {
		return $this->belongsTo('App\Models\School', 'xsh', 'dm');
	}

	/**
	 * 教研室
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  object     所属对象
	 */
	public function section() {
		return $this->belongsTo('App\Models\Section', 'jys', 'dm');
	}

}
