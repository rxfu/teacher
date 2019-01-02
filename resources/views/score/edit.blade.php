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
	                    	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#importModal" title="导入成绩">导入成绩</button>
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
                	<form id="scoresForm" name="scoresForm" action="{{ route('score.batchUpdate', $course->kcxh) }}" method="post" role="form">
                    	{{ method_field('put') }}
                    	{{ csrf_field() }}
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
                        	<tbody>
	                        	@foreach ($students as $student)
	                        		<tr id="{{ $student->xh }}"{!! config('constants.score.passline') > $student->zpcj ? ' class="danger"' : '' !!}>
	                        			<td>
	                        				<div class="form-control-static">{{ $student->xh }}</div>
	                        			</td>
	                        			<td>
	                        				<div class="form-control-static">{{ $student->xm }}</div>
	                        			</td>
	                        			@if (config('constants.score.uncommitted') === $student->tjzt)
	                        				@if (config('constants.score.deferral') === $student->kszt || config('constants.score.disqualification') === $student->kszt)
	                        					@foreach (array_pluck($ratios, 'id') as $id)
		                        					<td>
		                        						<div class="form-control-static">{{ $student->{'cj' . $id } }}</div>
		                        					</td>
		                        				@endforeach
	                        				@else
		                        				@foreach (array_pluck($ratios, 'id') as $id)
		                        					<td>
		                        						<input type="text" name="{{ $student->xh . $id }}" id="{{ $student->xh . $id }}" value="{{ $student->{'cj' . $id} }}" class="form-control">
		                        					</td>
		                        				@endforeach
		                        			@endif
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
	                        				@if (config('constants.score.deferral') === $student->kszt || config('constants.score.disqualification') === $student->kszt)
	                        					<div class="form-control-static">{{ $student->status->mc }}</div>
	                        				@else
		                        				<select name="{{ $student->xh . 'kszt' }}" id="{{ $student->xh . 'kszt' }}" class="form-control">
		                        					@foreach ($statuses as $status)
		                        						<option value="{{ $status->dm }}"{{ $status->dm == $student->kszt ? ' selected' : '' }}>{{ $status->mc }}</option>
		                        					@endforeach
		                        				</select>
	                        				@endif
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
                        	@if ($exists)
                        		<button type="submit" class="btn btn-success btn-right btn-circle btn-lg" title="保存成绩"><i class="glyphicon glyphicon-cloud-upload"></i></button>
                        	@endif
                    	</table>
	                </form>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" tabindex="-1" role="dialog" id="processing" data-backdrop="static" data-keyboard="false">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
			<div class="modal-header">
	        	<h1 class="modal-title">保存中……</h1>
	    	</div>
	      	<div class="modal-body">
	        	<div class="progress">
	        		<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
	        			<span class="sr-only">保存中……</span>
	        		</div>
	        	</div>
	      	</div>
    	</div><!-- /.modal-content -->
  	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" tabindex="-1" role="dialog" id="importModal">
  	<div class="modal-dialog" role="document">
    	<div class="modal-content">
	      	<form id="importForm" name="importForm" action="{{ route('score.import', $course->kcxh) }}" method="post" enctype="multipart/form-data" role="form">
	      		{{ csrf_field() }}
				<div class="modal-header">
	        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	        			<span aria-hidden="true">&times;</span>
	        		</button>
		        	<h4 class="modal-title">导入成绩</h4>
		    	</div>
		      	<div class="modal-body">
                    <label for="file" class="control-label">上传成绩</label>
                    <input type="file" name="file" id="file" placeholder="上传成绩" class="form-control" autofocus required>
		      	</div>
			    <div class="modal-footer">
			        <button type="button" class="btn btn-default" data-dismiss="modal" title="取消">取消</button>
			        <button type="submit" class="btn btn-primary" title="导入">导入</button>
			    </div>
		    </form>
    	</div><!-- /.modal-content -->
  	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@stop

@push('scripts')
<script>
$(function() {
	$('#scoresForm').on('submit', function(e) {
		$('#processing').modal();
	});
	$('#confirmForm').on('submit', function(e) {
		$('#processing').modal();
	});
	$('#importForm').on('submit', function(e){
		$('#importModal').modal('toggle');
		$('#processing').modal();
	});
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
					$('#status' + sno).text('保存中......').addClass('text-warning');
				},
				'success': function(data) {
					if ($.isNumeric(data)){
						$('#status' + sno).removeClass();
						$('#status' + sno).text('保存成功').addClass('text-success');

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
						$('#status' + sno).text('保存失败').addClass('text-danger');
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

				// $(this).closest('form').submit();
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