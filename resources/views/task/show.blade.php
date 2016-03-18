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
                                <th class="active">学号</th>
                                <th class="active">姓名</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>学号</th>
                                <th>姓名</th>
                            </tr>
                        </tfoot>
                        <tbody>
                        	@foreach ($students as $student)
                        		<tr>
                        			<td>{{ $student->xh }}</td>
                        			<td>{{ $student->xm }}</td>
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