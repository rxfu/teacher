@extends('app')

@section('content')
<section class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">得分情况</div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="active">一级指标</th>
                                <th class="active">二级指标</th>
                                <th class="active">二级指标得分</th>
                                <th class="active">一级指标得分</th>
                            </tr>
                        </thead>
                        <tbody>
                        	@if (count($scores))
	                            @foreach ($scores['zb'] as $key => $index)
		                            <tr>
		                                <td{!! $index['total'] ? ' rowspan="' . $index['total'] . '"' : '' !!}>{{ $key }}.{{ $index['zb_mc'] }}</td>
		                                <td>{{ $index['ejzb'][0]['ejzb_id'] }}.{{ $index['ejzb'][0]['ejzb_mc'] }}</td>
		                                <td>{{ $index['ejzb'][0]['score'] }}</td>
		                                <td{!! $index['total'] ? ' rowspan="' . $index['total'] . '"' : '' !!}>{{ $scores[$key] }}</td>
		                            </tr>
	                                @foreach ($index['ejzb'] as $k => $score)
	                                    @if (0 != $k)
	                                        <tr>
	                                            <td>{{ $score['ejzb_id'] }}.{{ $score['ejzb_mc'] }}</td>
	                                            <td>{{ $score['score'] }}</td>
	                                        </tr>
	                                    @endif
	                                @endforeach
	                            @endforeach
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="active text-right" colspan="4">综合评分：{{ count($scores) ? $scores['zhpf'] : 0 }}分</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">学生评语，共{{ count($comments) }}条评语</div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="active"><i>#</i></th>
                                <th class="active">优点</th>
                                <th class="active">缺点</th>
                                <th class="active">在教学方面，您的学生最想对您说的一句话</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th><i>#</i></th>
                                <th>优点</th>
                                <th>缺点</th>
                                <th>在教学方面，您的学生最想对您说的一句话</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php $i = 0;?>
                            @foreach ($comments as $comment)
	                            <tr>
	                                <td><i>#{{ ++$i }}</i></td>
	                                <td class="text-success">{{ $comment->c_yd }}</td>
	                                <td class="text-danger">{{ $comment->c_qd }}</td>
	                                <td class="text-info">{{ $comment->c_one }}</td>
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