@extends('app')

@section('content')
<section class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="active">课程代码</th>
                                <th class="active">课程名称</th>
                                <th class="active">校区</th>
                                <th class="active">周一</th>
                                <th class="active">周二</th>
                                <th class="active">周三</th>
                                <th class="active">周四</th>
                                <th class="active">周五</th>
                                <th class="active">周六</th>
                                <th class="active">周日</th>
                                <th class="active">年级</th>
                                <th class="active">专业</th>
                                <th class="active">理论学分</th>
                                <th class="active">实验学分</th>
                                <th class="active">总学分</th>
                                <th class="active">理论学时</th>
                                <th class="active">实验学时</th>
                                <th class="active">总学时</th>
                                <th class="active">考核方式</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>课程代码</th>
                                <th>课程名称</th>
                                <th>校区</th>
                                <th>周一</th>
                                <th>周二</th>
                                <th>周三</th>
                                <th>周四</th>
                                <th>周五</th>
                                <th>周六</th>
                                <th>周日</th>
                                <th>年级</th>
                                <th>专业</th>
                                <th>理论学分</th>
                                <th>实验学分</th>
                                <th>总学分</th>
                                <th>理论学时</th>
                                <th>实验学时</th>
                                <th>总学时</th>
                                <th>考核方式</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($courses as $course)
                                <tr>
                                    <td>{{ $course['kcxh'] }}</td>
                                    <td>{{ $course['kcmc'] }}</td>
                                    <td>{{ $course['xqh'] }}</td>
                                    @for ($week = 1; $week <= 7; $week++)
                                        <td{!! isset($course[$week]) ? ' class="warning"' : '' !!}>
                                            @if (isset($course[$week]))
                                                @foreach ($course[$week] as $class)
                                                    <p>
                                                        <div>第 {{ $class['ksz'] === $class['jsz'] ? $class['ksz'] : $class['ksz'] . ' ~ ' . $class['jsz'] }} 周</div>
                                                        <div class="text-danger"><strong>第 {{ $class['ksj'] === $class['jsj'] ? $class['ksj'] : $class['ksj'] . ' ~ ' . $class['jsj'] }} 节</strong></div>
                                                        <div class="text-warning">{{ empty($class['js']) ? '未知' : $class['js'] }}教室</div>
                                                    </p>
                                                @endforeach
                                            @endif
                                        </td>
                                    @endfor
                                    <td>{{ $course['nj'] }}</td>
                                    <td>{{ $course['zy'] }}</td>
                                    <td>{{ $course['llxf'] }}</td>
                                    <td>{{ $course['syxf'] }}</td>
                                    <td>{{ $course['zxf'] }}</td>
                                    <td>{{ $course['llxs'] }}</td>
                                    <td>{{ $course['syxs'] }}</td>
                                    <td>{{ $course['zxs'] }}</td>
                                    <td>{{ $course['kh'] }}</td>
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