<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * 显示并处理评教信息
 *
 * @author FuRonxin
 * @date 2016-03-30
 * @version 2.0
 */
class SetController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		//
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
	 * 显示评教结果
	 * @author FuRongxin
	 * @date    2016-03-30
	 * @version 2.0
	 * @param   \Illuminate\Http\Request  $request 评教结果请求
	 * @param   string $kcxh 12位课程序号
	 * @return  \Illuminate\Http\Response 评教结果
	 */
	public function show(Request $request, $kcxh) {
		$inputs = $request->all();

		$table = $inputs['year'] . $inputs['term'] . 't';
		$mark  = $inputs['year'] . $inputs['term'] . 'Mark';

		// 获取评教分值
		$score  = DB::connection('pgset')->selelctRaw('SELECT AVG(s_jxtd) AS jxtd, AVG(s_jxnr) AS jxnr, AVG(s_jxff) AS jxff, AVG(s_jxxg) AS jxxg, AVG(s_zhpf) As zhpf FROM "' . $table . '" WHERE c_kcbh = ? and c_jsgh = ? GROUP BY c_kcbh, c_jsgh')->firstOrFail();
		$scores = [
			'1'    => sprintf('%.2f', $score->jxtd),
			'2'    => sprintf('%.2f', $score->jxnr),
			'3'    => sprintf('%.2f', $score->jxff),
			'4'    => sprintf('%.2f', $score->jxxg),
			'zhpf' => sprintf('%.2f', $score->zhpf),
		];

		$categories = DB::connection('pgset')->selectRaw('SELECT a.zb_id, a.zb_mc, COUNT(*) AS total FROM t_zb_yjzb a, t_zb_ejzb b WHERE a.zb_id = b.zb_id GROUP BY a.zb_id, a.zb_mc ORDER BY a.zb_id')->get();
		foreach ($categories as $category) {
			$items = DB::connection('pgset')->selectRaw('SELECT ejzb_id, ejzb_mc, AVG(s_mark) AS mark FROM "' . $mark . '", t_zb_ejzb WHERE c_xm = CAST(ejzb_id AS TEXT) AND zb_id = ? AND c_kcbh = ? AND c_jsgh = ? GROUP BY ejzb_id, ejzb_mc, c_kcbh, c_jsgh, c_xm ORDER BY ejzb_id', [$categroy->zb_id, $kcxh, $Auth::user()->jsgh])->get();

			$scores['zb'][$category->zb_id] = [
				'zb_mc' => $category->zb_mc,
				'total' => $category->total,
			];

			foreach ($items as $item) {
				$scores['zb'][$category->zb_id]['ejzb'][] = [
					'ejzb_id' => $item->ejzb_id,
					'ejzb_mc' => $item->ejzb_mc,
					'score'   => sprintf('%.2f', $item->mark),
				];
			}
		}

		// 获取评教评语
		$comments = DB::connection('pgset')->selectRaw('SELECT c_yd, c_qd, c_one, c_xh FROM t_zl_xspy a INNER JOIN "' . $table . '" b ON a.c_kcxh = b.c_kcxh WHERE a.c_nd = ? AND a.c_xq = ? AND b.c_jsgh = ? AND b.c_kcbh = ?')->get();

		$title = session('year') . '年度' . Term::find(session('term'))->mc . '学期' . $kcxh . Course::find(Helper::getCno($kcxh))->mc . '课程';

		return view('set.show')
			->withTitle($title . '评教结果')
			->withScores($scores)
			->withComments($comments);
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
