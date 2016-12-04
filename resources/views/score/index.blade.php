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
                        		<tr{!! $task->selcourses->count() <= 0 ? ' class="danger"' : '' !!}>
                        			<td>{{ $task->kcxh }}</td>
                        			<td>{{ $task->course->kcmc }}</td>
                                    <td>{{ $task->course->xf }}</td>
                                    <td>{{ $task->course->xs }}</td>
                        			<td>{{ $task->selcourses->count() }}</td>
                        			<td>
                                        @if ($task->selcourses->count() <= 0)
                                            <span class="text-danger">上课人数为零</span>
                                        @else
                        				    <a href="{{ route('score.edit', $task->kcxh) }}" title="录入成绩" class="btn btn-primary">录入成绩</a>
                                            <a href="{{ route('task.show', $task->kcxh) }}" title="学生名单" class="btn btn-primary">学生名单</a>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importModal" title="导入成绩">导入成绩</button>
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

<div class="modal fade" tabindex="-1" role="dialog" id="importModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="importForm" name="importForm" action="{{ route('score.import', $task->kcxh) }}" method="post" role="form">
                {{ csrf_field() }}
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">导入成绩</h4>
                </div>
                <div class="modal-body">
                    <input type="file" id="import" name="import">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" title="取消">取消</button>
                    <button type="button" class="btn btn-primary" title="导入">导入</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@stop