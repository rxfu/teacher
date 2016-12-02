@extends('app')

@section('content')
<section class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <div class="pull-right">
                    <a href="{{ route('task.export', [$year, $term, $kcxh])}}" title="导出空白成绩单" class="btn btn-success" role="button">导出空白成绩单</a>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover data-table">
                        <thead>
                            <tr>
                                <th class="active">学号</th>
                                <th class="active">姓名</th>
                                <th class="active">年级</th>
                                <th class="active">专业</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>学号</th>
                                <th>姓名</th>
                                <th>年级</th>
                                <th>专业</th>
                            </tr>
                        </tfoot>
                        <tbody>
                        	@foreach ($students as $student)
                        		<tr>
                        			<td>{{ $student->xh }}</td>
                                    <td>{{ $student->xm }}</td>
                                    <td>{{ $student->student->nj }}</td>
                        			<td>{{ $student->student->major->mc }}</td>
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