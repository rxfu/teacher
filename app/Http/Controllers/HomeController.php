<?php

namespace App\Http\Controllers;

use App\Models\Term;

/**
 * 显示并处理系统消息
 *
 * @author FuRongxin
 * @date 2016-03-12
 * @version 2.0
 */
class HomeController extends Controller {

	/**
	 * 显示系统消息列表
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  \Illuminate\Http\Response 系统消息列表
	 */
	public function index() {
		$term = Term::find(session('term'));

		return view('home.index')
			->withTitle('综合管理系统')
			->withYear(session('year'))
			->withTerm($term->mc);
	}
}
