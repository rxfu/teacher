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
                                <th class="active">操作</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>年度</th>
                                <th>学期</th>
                                <th>操作</th>
                            </tr>
                        </tfoot>
                        <tbody>
                        	@foreach ($periods as $period)
                        		<tr>
                        			<td>{{ $period->nd }}</td>
                        			<td>{{ $period->term->mc }}</td>
                                    <td>
                                        <a href="{{ route('timetable.index', ['year' => $period->nd, 'term' => $period->xq]) }}" title="课程列表" class="btn btn-primary">课程列表</a>
                                        <a href="{{ route('timetable.show', ['year' => $period->nd, 'term' => $period->xq]) }}" title="课程列表" class="btn btn-primary">课程表</a>
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