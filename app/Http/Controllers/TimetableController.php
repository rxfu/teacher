<?php

namespace App\Http\Controllers;

use App\Http\Helper;
use App\Models\Course;
use App\Models\Timetable;
use Auth;
use Illuminate\Http\Request;

class TimetableController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	/**
	 * 显示课程列表
	 * @author FuRongxin
	 * @date    2016-03-18
	 * @version 2.0
	 * @param   \Illuminate\Http\Request $request 课程列表请求
	 * @return  \Illuminate\Http\Response 课程列表
	 */
	public function index(Request $request) {
		$inputs = $request->all();

		$timetables = Timetable::with(['campus', 'classroom'])
			->whereNd($inputs['year'])
			->whereXq($inputs['term'])
			->whereJsgh(Auth::user()->jsgh)
			->get();

		$courses = [];

		foreach ($timetables as $timetable) {

			// 生成课程序号为索引的课程信息数组
			if (!isset($courses[$timetable->kcxh])) {
				$courses[$timetable->kcxh] = [
					'kcxh' => $timetable->kcxh,
					'kcmc' => Course::find(Helper::getCno($timetable->kcxh))->kcmc,
					'xqh'  => $timetable->campus->mc,
				];
			}

			// 在课程信息数组下生成周次为索引的课程时间数组
			$courses[$timetable->kcxh][$timetable->zc][] = [
				'ksz' => $timetable->ksz,
				'jsz' => $timetable->jsz,
				'ksj' => $timetable->ksj,
				'jsj' => $timetable->jsj,
				'js'  => $timetable->classroom->mc,
			];
		}

		return view('timetable.index')
			->withTitle('课程列表')
			->withCourses($courses);
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
