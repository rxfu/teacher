<?php

namespace App\Http\Controllers;

use App\Http\Helper;
use App\Models\Course;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * 显示并处理评教信息
 *
 * @author FuRonxin
 * @date 2016-03-30
 * @version 2.0
 */
class SetController extends Controller {

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

		if (!Schema::connection('pgset')->hasTable($table)) {
			return back()->withStatus('没有评教数据');
		}

		// 获取评教分值
		$data = DB::connection('pgset')->selectOne('SELECT AVG(s_jxtd) AS jxtd, AVG(s_jxnr) AS jxnr, AVG(s_jxff) AS jxff, AVG(s_jxxg) AS jxxg, AVG(s_zhpf) As zhpf FROM "' . $table . '" WHERE c_kcxh = ? and c_jsgh = ? GROUP BY c_kcbh, c_jsgh', [$kcxh, Auth::user()->jwgh]);

		$scores = [];
		if (!is_null($data)) {
			$scores = [
				'1'    => sprintf('%.2f', $data->jxtd),
				'2'    => sprintf('%.2f', $data->jxnr),
				'3'    => sprintf('%.2f', $data->jxff),
				'4'    => sprintf('%.2f', $data->jxxg),
				'zhpf' => sprintf('%.2f', $data->zhpf),
			];

			$categories = DB::connection('pgset')->select('SELECT a.zb_id, a.zb_mc, COUNT(DISTINCT b.ejzb_id) AS total FROM t_zb_yjzb a, t_zb_ejzb b WHERE a.zb_id = b.zb_id GROUP BY a.zb_id, a.zb_mc ORDER BY a.zb_id');
			foreach ($categories as $category) {
				$items = DB::connection('pgset')->select('SELECT ejzb_id, ejzb_mc, AVG(s_mark) AS mark FROM "' . $mark . '", t_zb_ejzb WHERE c_xm = CAST(ejzb_id AS TEXT) AND zb_id = ? AND c_kcxh = ? AND c_jsgh = ? GROUP BY ejzb_id, ejzb_mc, c_kcbh, c_jsgh, c_xm ORDER BY ejzb_id', [$category->zb_id, $kcxh, Auth::user()->jwgh]);

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
		}

		// 获取评教评语
		$comments = DB::connection('pgset')->select('SELECT c_yd, c_qd, c_one, c_xh FROM t_zl_xspy a INNER JOIN "' . $table . '" b ON a.c_kcxh = b.c_kcxh WHERE a.c_nd = ? AND a.c_xq = ? AND b.c_jsgh = ? AND b.c_kcxh = ?', [$inputs['year'], $inputs['term'], Auth::user()->jwgh, $kcxh]);

		$title = Helper::getAcademicYear($inputs['year']) . '学年' . Term::find($inputs['term'])->mc . '学期' . $kcxh . Course::find(Helper::getCno($kcxh))->kcmc . '课程' . '评教结果';

		return view('set.show')
			->withTitle($title)
			->withScores($scores)
			->withComments($comments);
	}
}
