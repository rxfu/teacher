@extends('app')

@section('content')
<section class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
        	<div class="panel-heading">
        		<div class="panel-title">
        		成绩组成方式：
                {{ implode(':', array_pluck($ratios, 'name')) }} = {{ implode(':', array_map(function($n) { return $n / 10; }, array_pluck($ratios, 'value'))) }}
        		</div>
        	</div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover data-table">
                        <thead>
                            <tr>
                                <th class="active">学号</th>
                                <th class="active">姓名</th>
                                @foreach (array_pluck($ratios, 'name') as $name)
                                	<th class="active">{{ $name }}</th>
                                @endforeach
                                <th class="active">总评成绩</th>
                                <th class="active">考试状态</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($scores as $score)
                            <tr{!! $score->zpcj < config('constants.score.passline') ? ' class="danger"' : '' !!}>
                                <td>{{ $score->xh }}</td>
                                <td>{{ $score->xm }}</td>
                                @foreach (array_pluck($ratios, 'id') as $id)
                                	<td>{{ $score->{'cj' . $id} }}</td>
                                @endforeach
                                <td>{{ $score->zpcj }}</td>
                                <td>{{ $score->status->mc }}</td>
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