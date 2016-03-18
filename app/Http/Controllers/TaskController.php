<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Auth;
use Illuminate\Http\Request;

/**
 * 显示并处理教师任务书
 *
 * @author FuRongxin
 * @date 2016-03-12
 * @version 2.0
 */
class TaskController extends Controller {

	/**
	 * 显示教师课程列表
	 * @author FuRongxin
	 * @date    2016-03-12
	 * @version 2.0
	 * @return  \Illuminate\Http\Response 教师课程列表
	 */
	public function index() {
		$tasks = Task::with([
			'course' => function ($query) {
				$query->select('kch', 'kcmc', 'xs');
			},
			'term'])
			->whereJsgh(Auth::user()->jsgh)
			->orderBy('nd', 'desc')
			->orderBy('xq', 'desc')
			->orderBy('kcxh')
			->get();

		return view('task.index')
			->withTitle('历年课程列表')
			->withTasks($tasks);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		//
	}
}
