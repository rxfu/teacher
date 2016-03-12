<?php

namespace App\Http\Middleware;

use Closure;

/**
 * 去除请求两段空白字符
 *
 * @author FuRongxin
 * @date 2016-03-12
 * @version 2.0
 */
class TrimRequest {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		$request->merge(array_map('App\http\Helper::trimString', $request->all()));
		return $next($request);
	}
}
