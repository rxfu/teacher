@extends('app')

@section('content')
<section class="row">
    <div class="col-sm-12">
    	<div class="panel panel-default">
    		<div class="panel-body">
                现在开放录入<span class="text-danger"><strong>{{ App\Http\Helper::getAcademicYear($year) }}学年度{{ $term }}学期</strong></span>成绩
    		</div>
    	</div>
    </div>
</section>
@stop