@extends('app')

@section('content')
<section class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <div class="panel-title pull-left">
        		成绩组成方式：
                {{ implode(':', array_pluck($ratios, 'name')) }} = {{ implode(':', array_map(function($n) { return $n / 10; }, array_pluck($ratios, 'value'))) }}
        		</div>
        		@if ($exists)
	                <div class="pull-right">
	                    <form id="confirmForm" name="confirmForm" method="post" action="{{ url('score/confirm', $course->kcxh) }}" method="post" role="form" onsubmit="return confirm('注意：请检查成绩是否已经录入完毕并且正确，成绩确认后将不可更改！请问确定要上报成绩吗？')">
	                        <button type="button" class="btn btn-primary" title="成绩上报">成绩上报</button>
	                    </form>
	                </div>
        		@endif
            </div>
            <div class="panel-body">
                <div class="table-responsive tab-table">
                    <table class="table table-bordered table-striped table-hover data-table">
                        <thead>
                            <tr>
                                <th class="active">学号</th>
                                <th class="active">姓名</th>
                                @foreach (array_pluck($ratios, 'name') as $name)
                                	<th class="active">{{ $name }}</th>
                                @endforeach
                                <th class="active">总评</th>
                                <th class="active">状态</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<form id="scoreForm" name="scoreForm" action="{{ route('score.update', $course->kcxh) }}" method="post" role="form">
                        		{!! method_field('put') !!}
                        		{!! csrf_field() !!}
	                        	@foreach ($students as $student)
	                        		<tr>
	                        			<td>
	                        				<div class="form-control-static">{{ $student->xh }}</div>
	                        			</td>
	                        			<td>
	                        				<div class="form-control-static">{{ $student->xm }}</div>
	                        			</td>
	                        			@if (config('constants.score.uncommitted') == $student->tjzt)
	                        				@foreach (array_pluck($ratios, 'id') as $id)
	                        					<td>
	                        						<input type="text" name="{{ $student->xh . $id }}" id="{{ $student->xh . $id }}" value="{{ $student->{'cj' . $id} }}" class="form-control">
	                        					</td>
	                        				@endforeach
	                        			@else
	                        				@foreach (array_pluck($ratios, 'id') as $id)
	                        					<td>
	                        						<div class="form-control-static">{{ $student->{'cj' . $id } }}</div>
	                        					</td>
	                        				@endforeach
	                        			@endif
	                        			<td>
	                        				<div class="form-control-static"><span id="total{{ $student->xh . $id }}">{{ $student->zpcj }}</span></div>
	                        			</td>
	                        			<td>
	                        			@if (config('constants.score.uncommitted') == $student->tjzt)
	                        				<select name="{{ $student->xh . 'tjzt' }}" id="{{ $student->xh . 'tjzt' }}" class="form-control">
	                        					@foreach ($statuses as $status)
	                        						<option value="{{ $status->dm }}"{{ $status->dm == $student->tjzt ? ' selected' : '' }}>{{ $status->mc }}</option>
	                        					@endforeach
	                        				</select>
	                        			@else
	                        				<div class="form-control-static">{{ $student->status->mc }}</div>
	                        			@endif
	                        			</td>
	                        		</tr>
	                        	@endforeach
	                        </form>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@stop

@push('scripts')
<script>
$(function() {
	$('#scoreForm input').on('change', function() {alert('change');
		// Use ajax to submit form data
		$.ajax({
			'url': '{{ route('score.update', $course->kcxh) }}',
			'type': 'post',
			'data': {
				'_method': 'put',
				'csrf': '{!! csrf_token() !!}',
				'dataType': 'json',
				'score': this.val(),
				'xh': this.attr('name').substring(0, 12),
				'id': this.attr('name').substring(12, 13)
			},
			'success': function(data) {
				$('#total' + this.attr('name')).text(data);
			}
		});
	});

	$('#scoreForm input').on('keypress', function(e) {
		// Enter pressed
		if (e.keyCode == 13) {
			var inputs = $(this).parents('table').find('input');
			var idx = inputs.index(this);

			if (idx == inputs.length - 1) {
				inputs[0].select();
			} else {
				inputs[idx + 1].focus();
				inputs[idx + 1].select();
			}

			$(this).closest('form').submit();
			return false;
		}
	});
});
</script>
@endpush