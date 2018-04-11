<?php

namespace App\Providers;

use App\Models\Dcxmxt;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot() {
		view()->composer('app', function ($view) {

			// 是否允许申请大创项目
			// 2018-03-28：应教务处要求添加
			$allowed_dcxm = Dcxmxt::find('XT_KG')->value;

			$view->with('allowed_dcxm', $allowed_dcxm);
		});
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {
		//
	}
}
