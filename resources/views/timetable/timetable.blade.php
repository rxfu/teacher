@extends('app')

@section('content')
<section class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="2" class="active">节次</th>
                                <th class="active">星期一</th>
                                <th class="active">星期二</th>
                                <th class="active">星期三</th>
                                <th class="active">星期四</th>
                                <th class="active">星期五</th>
                                <th class="active">星期六</th>
                                <th class="active">星期日</th>
                            </tr>
                        </thead>
                        <tbody>
                        	@for ($i = $periods['morning']['begin']; $i <= $periods['evening']['end']; ++$i)
                                <tr>
                            		@if ($id = array_search($i, array_column($periods, 'begin', 'id')))
                                        <th rowspan="{{ $periods[$id]['end'] - $periods[$id]['begin'] + 1 }}" class="active text-cener">{{ $periods[$id]['name'] }}</th>
                            		@endif
                                    <th class="active">第{{ $i }}节</th>
                                    @for ($j = 1; $j <= 7; ++$j)
                                        @if ($rows = $courses[$i][$j]['rend'] - $courses[$i][$j]['rbeg'] + 1)
                                            <td{!! 1 < $rows ? ' rowspan="' . $rows . '"' : '' !!}{!! isset($courses[$i][$j]['conflict']) ? ' class="warning"' : '' !!}>
                                                @foreach (array_filter($courses[$i][$j], function($v) { return is_array($v); }) as $course)
                                                    <p>
                                                        <div class="text-danger"><strong>{{ $course['kcmc'] }}</strong></div>
                                                        <div>第 {{ $course['ksz'] === $course['jsz'] ? $course['ksz'] : $course['ksz'] . ' ~ ' . $course['jsz'] }} 周</div>
                                                        <div class="text-success">第 {{ $course['ksj'] === $course['jsj'] ? $course['ksj'] : $course['ksj'] . ' ~ ' . $course['jsj'] }} 节</div>
                                                        <div class="text-warning">{{ empty($course['xqh']) ? '未知' : $course['xqh'] }}校区{{ empty($course['js']) ? '未知' : $course['js'] }}教室</div>
                                                    </p>
                                                @endforeach
                                            </td>
                                        @endif
                                    @endfor
                                </tr>
                                @if ($id = array_search($i, array_column($periods, 'end', 'id')))
	                                <tr>
	                                    <td colspan="9" class="active text-center">{{ $periods[$id]['rest'] }}</td>
	                                </tr>
                                @endif
                        	@endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@stop