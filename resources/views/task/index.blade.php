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
                                <th class="active">年度</th>
                                <th class="active">学期</th>
                                <th class="active">课程序号</th>
                                <th class="active">课程名称</th>
                                <th class="active">学分</th>
                                <th class="active">学时</th>
                                <th class="active">上课人数</th>
                                <th class="active">操作</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>年度</th>
                                <th>学期</th>
                                <th>课程序号</th>
                                <th>课程名称</th>
                                <th>学分</th>
                                <th>学时</th>
                                <th>上课人数</th>
                                <th>操作</th>
                            </tr>
                        </tfoot>
                        <tbody>
                        	@foreach ($tasks as $task)
                        		<tr{!! $task->scores->count() <= 0 ? ' class="danger"' : '' !!}>
                        			<td>{{ $task->nd }}</td>
                        			<td>{{ $task->term->mc }}</td>
                        			<td>{{ $task->kcxh }}</td>
                        			<td>{{ $task->course->kcmc }}</td>
                                    <td>{{ $task->course->xf }}</td>
                                    <td>{{ $task->course->xs }}</td>
                        			<td>{{ $task->scores->count() }}</td>
                        			<td>
                                        <a href="{{ route('task.show', [$task->kcxh, 'year' => $task->nd, 'term' => $task->xq]) }}" title="学生名单" class="btn btn-primary">学生名单</a>
                                        @if ($task->scores->count() <= 0)
                                            <span class="text-danger">成绩未确认相关查询暂不能使用</span>
                                        @else
                        				    <a href="{{ route('score.show', [$task->kcxh, 'year' => $task->nd, 'term' => $task->xq]) }}" title="查询成绩" class="btn btn-primary">查询成绩</a>
                                            <a href="{{ route('set.show', [$task->kcxh, 'year' => $task->nd, 'term' => $task->xq]) }}" title="查询评教结果" class="btn btn-primary">查询评教结果</a>
                                            <a href="{{ route('tes.show', [$task->kcxh, 'year' => $task->nd, 'term' => $task->xq]) }}" title="查询评学结果" class="btn btn-primary">查询评学结果</a>
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