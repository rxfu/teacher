<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;

/**
 * 监听登出事件
 *
 * @author FuRongxin
 * @date 2016-03-12
 * @version 2.0
 */
class AuthLogoutListener {

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
	 * @param  Logout  $event
	 * @return void
	 */
	public function handle(Logout $event) {
		request()->session()->flush();
	}
}
