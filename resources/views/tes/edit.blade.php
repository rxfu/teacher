@extends('app')

@section('content')
<section class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <form name="tesForm" id="tesForm" action="{{ route('tes.update', $kcxh) }}" method="post" role="form">
                	{!! method_field('put') !!}
                	{!! csrf_field() !!}
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
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $item->xh }}</td>
                                        <td>{{ $item->category->mc }}</td>
                                        <td>{{ $item->mc }}</td>
                                        <td>{{ $item->fz }}</td>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="score[{{ $item->id }}][fz]" placeholder="评分分值" data-fv-notempty="true" data-fv-integer="true" min="0" max="{{ $item->zgfz}}" required>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5">备注：优秀（90分以上）、良好（80~89分）、中等（70~79分）、合格（60~69分）、不合格（59分以下）</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="col-lg-4 col-lg-offset-4">
                        <button type="submit" name="submitScore" title="提交评分" class="btn btn-primary btn-block">提交评分</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@stop