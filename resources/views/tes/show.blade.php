@extends('app')

@section('content')
<section class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="active">序号</th>
                                <th class="active">评价指标</th>
                                <th class="active">评价标准</th>
                                <th class="active">最高分值</th>
                                <th class="active">评分分值</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>序号</th>
                                <th>评价指标</th>
                                <th>评价标准</th>
                                <th>最高分值</th>
                                <th>评分分值</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @foreach ($results as $result)
                                <tr>
                                    <td>{{ $result->item->xh }}</td>
                                    <td>{{ $result->item->category->mc }}</td>
                                    <td>{{ $result->item->mc }}</td>
                                    <td>{{ $result->item->fz }}</td>
                                    <td>{{ $result->fz }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <th>评价等级与总分</th>
                                <th>等级</th>
                                <td>{{ $grade }}</td>
                                <th>总分</th>
                                <td>{{ $total }}</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5">备注：优秀（90分以上）、良好（80~89分）、中等（70~79分）、合格（60~69分）、不合格（59分以下）</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@stop