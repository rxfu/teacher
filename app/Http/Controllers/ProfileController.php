<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Auth;

/**
 * 显示并处理个人资料
 *
 * @author FuRongxin
 * @date 2016-03-12
 * @version 2.0
 */
class ProfileController extends Controller {

	/**
	 * 显示个人资料列表
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  \Illuminate\Http\Response 个人资料列表
	 */
	public function index() {
		return view('profile.index')
			->withTitle('个人资料')
			->withProfile(Aut::user());
	}

}
