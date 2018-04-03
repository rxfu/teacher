<?php

namespace App\Http\Controllers;

use App\Models\Dcxmsq;
use App\Models\Dcxmxx;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;

/**
 * 显示并处理大创项目信息
 *
 * @author FuRongxin
 * @date 2018-02-03
 * @version 2.3
 */
class DcxmController extends Controller {

	/**
	 * 显示大创项目列表
	 *
	 * @author FuRongxin
	 * @date    2018-02-03
	 * @version 2.3
	 * @return  \Illuminate\Http\Response 大创项目列表
	 */
	public function getList() {
		$projects = Dcxmxx::whereJsgh(Auth::user()->jsgh)
			->orderBy('cjsj', 'desc')
			->get();
		$title = '项目列表';

		return view('dcxm.list', compact('title', 'projects'));
	}

	/**
	 * 大创项目教师意见
	 *
	 * @author FuRongxin
	 * @date    2018-02-04
	 * @version 2.3
	 * @return  \Illuminate\Http\Response 大创项目基本信息
	 */
	public function getOpinion($id) {
		$project = Dcxmxx::findOrFail($id);
		$title   = '教师意见';

		return view('dcxm.opinion', compact('title', 'project'));
	}

	/**
	 * 保存大创项目教师意见
	 *
	 * @author FuRongxin
	 * @date    2018-02-04
	 * @version 2.3
	 * @param   \Illuminate\Http\Request $request 教师意见请求
	 * @return  \Illuminate\Http\Response 大创项目教师意见
	 */
	public function postOpinion(Request $request, $id) {
		if ($request->isMethod('post')) {
			$this->validate($request, [
				'jsyj' => 'required|string',
			]);
			$inputs = $request->all();

			$xmsq         = Dcxmsq::findOrFail($id);
			$xmsq->jsyj   = $inputs['jsyj'];
			$xmsq->jsyjsj = Carbon::now();

			if ($xmsq->save()) {
				return redirect('dcxm/list')->withStatus('教师意见保存成功');
			} else {
				return back()->withErrors();
			}
		}
	}

	/**
	 * 下载证明材料
	 * @author FuRongxin
	 * @date    2018-01-18
	 * @version 2.3
	 * @return  \Illuminate\Http\Response 证明材料
	 */
	public function getFile($id) {
		$xmsq     = Dcxmsq::findOrFail($id);
		$filename = $xmsq->zmcl;

		if (Storage::exists($filename)) {
			$file = Storage::get($filename);

			return response()->download(storage_path('uploads/' . $filename));
		}
	}

	/**
	 * 显示PDF申报书
	 * @author FuRongxin
	 * @date    2018-01-27
	 * @version 2.3
	 * @return  \Illuminate\Http\Response PDF申报书
	 */
	public function getPdf($id) {
		$project = Dcxmxx::with('student', 'application', 'funds')->findOrFail($id);
		$title   = '广西高校大学生创新创业计划项目申报书';

		return PDF::loadView('dcxm.pdf', compact('title', 'project'))
			->setPaper('a4')
			->setOption('margin-top', '3.7cm')
			->setOption('margin-bottom', '3.5cm')
			->setOption('margin-left', '2.8cm')
			->setOption('margin-right', '2.6cm')
			->inline($project->student->xh . '.pdf');
	}

	/**
	 * 获取PDF申报书
	 * @author FuRongxin
	 * @date    2018-01-27
	 * @version 2.3
	 * @return  \Illuminate\Http\Response PDF申报书
	 */
	public function getDownloadPdf($id) {
		return PDF::loadFile(url('dcxm/pdf/' . $id))->download('application.pdf');
	}
}
