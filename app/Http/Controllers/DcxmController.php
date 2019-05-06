<?php

namespace App\Http\Controllers;

use App\Models\Dcxmps;
use App\Models\Dcxmsq;
use App\Models\Dcxmxt;
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
class DcxmController extends Controller
{

    /**
     * 显示大创项目列表
     *
     * @author FuRongxin
     * @date    2018-02-03
     * @version 2.3
     * @return  \Illuminate\Http\Response 大创项目列表
     */
    public function getList()
    {
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
    public function getOpinion($id)
    {
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
    public function postOpinion(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'jsyj' => 'required|string',
            ]);
            $inputs = $request->all();

            $xmsq         = Dcxmsq::findOrFail($id);
            $xmsq->jsyj   = $inputs['jsyj'];
            $xmsq->jsyjsj = Carbon::now();

            $xmxx         = Dcxmxx::findOrFail($xmsq->xm_id);
            $xmxx->jssfty = $inputs['jssfty'];
            $xmxx->save();

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
    public function getFile($id)
    {
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
    public function getPdf($id)
    {
        $project = Dcxmxx::with('student', 'application', 'funds')->findOrFail($id);
        $title   = '广西高校大学生创新创业计划项目申报书';

        return PDF::loadView('dcxm.pdf', compact('title', 'project'))
            ->setPaper('a4')
            ->setOption('margin-top', '1.5cm')
            ->setOption('margin-bottom', '1.5cm')
            ->setOption('margin-left', '2.3cm')
            ->setOption('margin-right', '2.3cm')
            ->inline($project->student->xh . '.pdf');
    }

    /**
     * 获取PDF申报书
     * @author FuRongxin
     * @date    2018-01-27
     * @version 2.3
     * @return  \Illuminate\Http\Response PDF申报书
     */
    public function getDownloadPdf($id)
    {
        return PDF::loadFile(url('dcxm/pdf/' . $id))->download('application.pdf');
    }

    /**
     * 显示大创项目评审列表
     *
     * @author FuRongxin
     * @date    2019-02-18
     * @version 2.3
     * @return  \Illuminate\Http\Response 大创项目列表
     */
    public function getPslist()
    {
        $psjb = Dcxmxt::find('PS_JB')->value;
        if ($psjb == 0) {
            $projects = Dcxmxx::whereJssfty(config('constants.status.enable'));
        } elseif ($psjb == 1) {
            $projects = Dcxmxx::whereXysfty(config('constants.status.enable'));
        }

        $projects = $projects->with(['category', 'subject', 'reviews'])
        ->whereHas('reviews', function ($query) use ($psjb) {
            $query->whereZjgh(Auth::user()->jsgh)
            ->wherePsjb($psjb)
            ->whereNd(date('Y'));
        })
        ->orderBy('cjsj', 'desc')
        ->get();

        $title    = '评审列表';

        return view('dcxm.pslist', compact('title', 'projects'));
    }

    /**
     * 大创项目评审意见
     *
     * @author FuRongxin
     * @date    2018-02-188
     * @version 2.3
     * @return  \Illuminate\Http\Response 大创项目基本信息
     */
    public function getXmps($id)
    {
        $project = Dcxmxx::findOrFail($id);
        $title   = '评审意见';

        if (Dcxmps::whereXmId($project->id)->whereZjgh(Auth::user()->jsgh)->wherePsjb(Dcxmxt::find('PS_JB')->value)->whereNd(date('Y'))->exists()) {
            $review = Dcxmps::whereXmId($project->id)
                ->whereZjgh(Auth::user()->jsgh)
                ->wherePsjb(Dcxmxt::find('PS_JB')->value)
	            ->whereNd(date('Y'))
                ->first();
        } else {
            $review = null;
        }

        return view('dcxm.xmps', compact('title', 'project', 'review'));
    }

    /**
     * 保存大创项目评审意见
     *
     * @author FuRongxin
     * @date    2019-02-18
     * @version 2.3
     * @param   \Illuminate\Http\Request $request 评审意见请求
     * @return  \Illuminate\Http\Response 大创项目评审意见
     */
    public function postXmps(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'pf' => 'required|numeric|max:100|min:0',
            ]);
            $inputs = $request->all();

            if (Dcxmps::whereXmId($id)->whereZjgh(Auth::user()->jsgh)->wherePsjb(Dcxmxt::find('PS_JB')->value)->whereNd(date('Y'))->exists()) {
                $xmps = Dcxmps::whereXmId($id)
                    ->whereZjgh(Auth::user()->jsgh)
                    ->wherePsjb(Dcxmxt::find('PS_JB')->value)
            		->whereNd(date('Y'))
                    ->first();
            } else {
                $xmps = new Dcxmps;
            }
            $xmps->xm_id = $id;
            $xmps->psyj  = $inputs['psyj'];
            $xmps->pf    = $inputs['pf'];
            $xmps->zjgh  = Auth::user()->jsgh;
            $xmps->nd    = Carbon::now()->year;
            $xmps->psjb  = Dcxmxt::find('PS_JB')->value;

            if ($xmps->save()) {
                return redirect('dcxm/pslb')->withStatus('评审意见保存成功');
            } else {
                return back()->withErrors();
            }
        }
    }
}
