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
	                    <form id="confirmForm" name="confirmForm" action="{{ url('score/confirm', $course->kcxh) }}" method="post" role="form" onsubmit="return confirm('注意：请检查成绩是否已经录入完毕并且正确，成绩确认后将不可更改！请问确定要上报成绩吗？')">
	                    	{!! csrf_field() !!}
	                        <button type="submit" class="btn btn-primary" title="成绩上报">成绩上报</button>
	                    </form>
	                </div>
        		@endif
            </div>
            <div class="panel-body">
            	<div class="alert alert-danger" role="alert">
            		<strong>注意：</strong>录入成绩自动保存，无需点击提交按钮。点击“<strong>成绩上报</strong>”后，<strong>成绩不可更改！</strong>请老师在录入一项成绩后使用回车键，才会确认成绩的录入并移到下一格和生成总评成绩。
            	</div>
                <div class="table-responsive">
                    <table id="scores-table" class="table table-bordered table-striped table-hover data-table">
                        <thead>
                            <tr>
                                <th class="active">学号</th>
                                <th class="active">姓名</th>
                                @foreach (array_pluck($ratios, 'name') as $name)
                                	<th class="active">{{ $name }}</th>
                                @endforeach
                                <th class="active">总评</th>
                                <th class="active">考试状态</th>
                                <th class="active">提交状态</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>学号</th>
                                <th>姓名</th>
                                @foreach (array_pluck($ratios, 'name') as $name)
                                	<th>{{ $name }}</th>
                                @endforeach
                                <th>总评</th>
                                <th>考试状态</th>
                                <th>提交状态</th>
                            </tr>
                        </tfoot>
                        <form id="scoresForm" name="scoresForm" action="{{ route('score.batchUpdate') }}" method="post" role="form">
                        	{{ method_field('put') }}
                        	{{ csrf_field() }}
                        	<tbody>
	                        	@foreach ($students as $student)
	                        		<tr id="{{ $student->xh }}"{!! config('constants.score.passline') > $student->zpcj ? ' class="danger"' : '' !!}>
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
	                        				<div class="form-control-static"><span id="total{{ $student->xh }}">{{ $student->zpcj }}</span></div>
	                        			</td>
	                        			<td>
	                        			@if (config('constants.score.uncommitted') == $student->tjzt)
	                        				<select name="{{ $student->xh . 'kszt' }}" id="{{ $student->xh . 'kszt' }}" class="form-control">
	                        					@foreach ($statuses as $status)
	                        						<option value="{{ $status->dm }}"{{ $status->dm == $student->tjzt ? ' selected' : '' }}>{{ $status->mc }}</option>
	                        					@endforeach
	                        				</select>
	                        			@else
	                        				<div class="form-control-static">{{ $student->status->mc }}</div>
	                        			@endif
	                        			</td>
	                        			<td>
	                        				<div id="status{{ $student->xh }}">
	                        					@if (config('constants.score.uncommitted') == $student->tjzt)
	                        						未上报
	                        					@elseif (config('constants.score.committed') == $student->tjzt)
	                        						已上报
	                        					@endif
	                        				</div>
	                        			</td>
	                        		</tr>
	                        	@endforeach
                        	</tbody>
                        	<button type="submit" class="btn btn-success btn-right btn-circle btn-lg" title="保存成绩"><i class="glyphicon glyphicon-cloud-upload"></i></button>
	                    </form>
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
	$('tr').on({
		'focusin': function() {
			$(this).addClass('warning');
			$(this).children('td').css('font-weight', 'bold');
		},
		'focusout': function() {
			$(this).removeClass('warning');
			$(this).children('td').css('font-weight', 'normal');
		}
	});
	$('td').on('click', function() {
		$(this).children('input').select();
	});
	$('td').on({
		'click': function() {
			$(this).select();
		},
		'change': function() {
			var sno = $(this).attr('name').substring(0, 12);
			var id = $(this).attr('name').substring(12, 13);

			// Use ajax to submit form data
			$.ajax({
				'headers': '{{ csrf_token() }}',
				'url': '{{ route('score.update', $course->kcxh) }}',
				'type': 'post',
				'data': {
					'_method': 'put',
					'_token': '{{ csrf_token() }}',
					'dataType': 'json',
					'score': $(this).val(),
					'sno': sno,
					'id': id
				},
				'beforeSend': function() {
					$('#status' + sno).text('提交中......').addClass('text-warning');
				},
				'success': function(data) {
					if ($.isNumeric(data)){
						$('#status' + sno).removeClass();
						$('#status' + sno).text('提交成功').addClass('text-success');

						if ({{ config('constants.score.passline') }} > data) {
							$('tr#' + sno).removeClass('success');
							$('tr#' + sno).addClass('danger');

							$('#total' + sno).removeClass();
							$('#total' + sno).text(data).addClass('text-danger');
						} else {
							$('tr#' + sno).removeClass('danger');
							$('tr#' + sno).addClass('success');

							$('#total' + sno).removeClass();
							$('#total' + sno).text(data).addClass('text-success');
						}
					} else {
						$('#status' + sno).removeClass();
						$('#status' + sno).text('提交失败').addClass('text-danger');
					}
				}
			})
			.fail(function(jqXHR) {
				if (422 == jqXHR.status) {
					$.each(jqXHR.responseJSON, function(key, value) {
						$('#status' + sno).text(value).addClass('text-danger');
					});
				}
			});
		},
		'keypress': function(e) {
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
		}
	}, 'input');
	$('td').on({
		'change': function() {
			var sno = $(this).attr('name').substring(0, 12);

			// Use ajax to submit form data
			$.ajax({
				'headers': '{{ csrf_token() }}',
				'url': '{{ url('score/updateStatus', $course->kcxh) }}',
				'type': 'post',
				'data': {
					'_method': 'put',
					'_token': '{{ csrf_token() }}',
					'dataType': 'json',
					'status': $(this).val(),
					'sno': sno
				},
				'beforeSend': function() {
					$('#status' + sno).text('状态更新中......').addClass('text-warning');
				},
				'success': function(data) {
					if ($.isNumeric(data)){
						$('#status' + sno).removeClass();
						$('#status' + sno).text('状态更新成功').addClass('text-success');
					} else {
						$('#status' + sno).removeClass();
						$('#status' + sno).text('状态更新失败').addClass('text-danger');
					}
				}
			})
			.fail(function(jqXHR) {
				if (422 == jqXHR.status) {
					$.each(jqXHR.responseJSON, function(key, value) {
						$('#status' + sno).text(value).addClass('text-danger');
					});
				}
			});
		}
	}, 'select');
});
</script>
@endpush