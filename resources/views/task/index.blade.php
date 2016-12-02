@extends('app')

@section('content')
<section class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover data-table">
                        <thead>
                            <tr>
                                <th class="active">学年</th>
                                <th class="active">学期</th>
                                <th class="active">课程序号</th>
                                <th class="active">课程名称</th>
                                <th class="active">理论学分</th>
                                <th class="active">实验学分</th>
                                <th class="active">总学分</th>
                                <th class="active">理论学时</th>
                                <th class="active">实验学时</th>
                                <th class="active">总学时</th>
                                <th class="active">查询</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>学年</th>
                                <th>学期</th>
                                <th>课程序号</th>
                                <th>课程名称</th>
                                <th>理论学分</th>
                                <th>实验学分</th>
                                <th>总学分</th>
                                <th>理论学时</th>
                                <th>实验学时</th>
                                <th>总学时</th>
                                <th>查询</th>
                            </tr>
                        </tfoot>
                        <tbody>
                        	@foreach ($tasks as $task)
                        		<tr{!! $task->total <= 0 ? ' class="danger"' : '' !!}>
                                    <td>{{ App\Http\Helper::getAcademicYear($task->nd) }}</td>
                        			<td>{{ $task->xqmc }}</td>
                        			<td>{{ $task->kcxh }}</td>
                        			<td>{{ $task->kcmc }}</td>
                                    <td>{{ $task->llxf }}</td>
                                    <td>{{ $task->syxf}}</td>
                                    <td>{{ $task->zxf }}</td>
                                    <td>{{ $task->llxs }}</td>
                                    <td>{{ $task->syxs }}</td>
                                    <td>{{ $task->zxs }}</td>
                        			<td>
                                        <a href="{{ route('timetable.index', ['year' => $task->nd, 'term' => $task->xq]) }}" title="课程列表" class="btn btn-primary">课程列表</a>
                                        <a href="{{ route('timetable.show', [$task->nd, 'term' => $task->xq]) }}" title="课程表" class="btn btn-primary">课程表</a>
                                        <a href="{{ route('task.show', [$task->kcxh, 'year' => $task->nd, 'term' => $task->xq]) }}" title="学生名单" class="btn btn-primary">学生名单</a>
                    				    <a href="{{ route('score.show', [$task->kcxh, 'year' => $task->nd, 'term' => $task->xq]) }}" title="成绩单" class="btn btn-primary">成绩单</a>
                                        <a href="{{ route('set.show', [$task->kcxh, 'year' => $task->nd, 'term' => $task->xq]) }}" title="评教结果" class="btn btn-primary">评教结果</a>
                                        <a href="{{ route('tes.show', [$task->kcxh, 'year' => $task->nd, 'term' => $task->xq]) }}" title="评学结果" class="btn btn-primary">评学结果</a>
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