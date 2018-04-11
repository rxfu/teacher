@extends('app')

@section('content')
<section class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="panel-title">项目列表</div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="active">项目编号</th>
                                <th class="active">项目名称</th>
                                <th class="active">项目类别</th>
                                <th class="active">所属学科</th>
                                <th class="active">申请时间</th>
                                <th class="active">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($projects as $project)
                            <tr>
                            	<td>{{ $project->xmbh }}</td>
                                <td>
                                    {{ $project->xmmc }}
                                </td>
                                <td>{{ $project->category->mc }}</td>
                                <td>{{ $project->subject->mc }}</td>
                                <td>{{ date('Y-m-d', strtotime($project->cjsj)) }}</td>
                                <td>
                                    <a href="{{ url('dcxm/jsyj/' . $project->id) }}" title="填写教师意见" role="button" class="btn btn-success">填写教师意见</a>
                                    @if (!is_null($project->application))
                                        <a href="{{ url('dcxm/pdf/' . $project->id) }}" title="下载申报书" role="button" class="btn btn-warning">下载申报书</a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@stop