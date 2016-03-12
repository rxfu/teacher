@extends('app')

@section('content')
<section class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover datatable">
                        <thead>
                            <tr>
                                <th class="active">年度</th>
                                <th class="active">学期</th>
                                <th class="active">课程序号</th>
                                <th class="active">课程名称</th>
                                <th class="active">学时</th>
                                <th class="active">操作</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>年度</th>
                                <th>学期</th>
                                <th>课程序号</th>
                                <th>课程名称</th>
                                <th>学时</th>
                                <th>操作</th>
                            </tr>
                        </tfoot>
                        <tbody>
                        	@foreach ($tasks as $task)
                        		<tr>
                        			<td>{{ $task->nd }}</td>
                        			<td>{{ $task->xq }}</td>
                        			<td>{{ $task->kcxh }}</td>
                        			<td>{{ $task->kcmc }}</td>
                        			<td>{{ $task->xs }}</td>
                        			<td>
                        				<a href="#" title="查询成绩">查询成绩</a>
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