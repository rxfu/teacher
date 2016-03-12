<?php

namespace App\Listeners;

use App\Models\Setting;
use Auth;
use Illuminate\Auth\Events\Login;

/**
 * 监听登录事件
 *
 * @author FuRongxin
 * @date 2016-03-12
 * @version 2.0
 */
class AuthLoginListener {

	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct() {
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  Login  $event
	 * @return void
	 */
	public function handle(Login $event) {
		session([
			'year' => Setting::find('CJ_WEB_ND')->value,
			'term' => Setting::find('CJ_WEB_XQ')->value,
		]);
	}
}
