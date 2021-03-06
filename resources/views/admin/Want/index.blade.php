@extends('layouts.main')

@section('head_content')
<link id="bootstrap-style" href="{{ URL::asset('css/tcl/bootstrap-datepicker.min.css') }}" rel="stylesheet">
	<style type="text/css">
		.one_line{
			width:40%;
			margin-bottom:5px;
		}
		ul.dropdown-menu::after, ul.dropdown-menu::before{
			top: -1px;
			left: 10px;
			border-right: 9px solid transparent;
			border-bottom: 9px solid #222 !important;
			border-left: 9px solid transparent;
			content: none;
		}
		ul.dropdown-menu{
			min-width:100%;
		}
	</style>
@endsection

@section('BreadcrumbTrail')
	<ul class="breadcrumb">
		<li>
			<i class="icon-home"></i>
			<a href="{{route('admin.index')}}">主页</a>  
			<i class="icon-angle-right"></i>
		</li>
		<li><a href="javascript:void(0);">求购信息列表</a></li>
	</ul>
@endsection

@section('content')

@include('layouts.message')
	<div class="row-fluid sortable">		
		<div class="box span12">
			<div class="box-content">
				<!-- <ul style="background: none repeat scroll 0 0 #eee;border: 0 none;border-radius: 0;box-shadow: none;color: #aaa;line-height: 34px; margin: 0;margin-bottom:5px;">
					<li style="display: inline-block;line-height: 20px;">
						<a class="btn btn-primary" href="{{route('admin.want.create')}}">添加求购信息</a>
					</li>
					<li style="display: inline-block;line-height: 20px;">
						<a href="#" onclick="window.history.go(-1);return false;" class="btn ">返回</a>
					</li>
				</ul> -->
				<div class="page-tabs">
            		<ul class="nav nav-tabs">
            		  <!-- <li class="select_want_status @if($want_status_current == 1) active @endif" >
            		    <a href="javascript:void(0);" data-status="1">正常求购信息</a>
            		  </li>
            		  <li class="select_want_status @if($want_status_current == 2) active @endif">
            		    <a href="javascript:void(0);" data-status="2">待跟进求购信息</a>
            		  </li>
            		  <li class="select_want_status @if($want_status_current == 0) active @endif" >
            		    <a href="javascript:void(0);" data-status="0">已废弃求购信息</a>
            		  </li> -->
            		  <li style="display: inline-block;line-height:20px;">
						<a class="btn btn-search" href="#"><i class="halflings-icon search"></i>搜索求购信息</a>
					</li>
            		  <li style="display: inline-block;line-height:20px;float:right;">
						<a class="btn btn-primary" href="{{route('admin.want.create')}}">添加求购信息</a>
					</li>
					<li style="display:inline-block;line-height:20px;float:right;">
						<a href="#" onclick="window.history.go(-1);return false;" class="btn ">返回</a>
					</li>
            		</ul>
        		</div>

				<table  class="table table-striped table-bordered">
					<thead>
						<tr>
							<th>编号</th>
							<th>车型</th>
							<th>价格</th>
							<!-- <th>年限</th> -->
							<th>里程</th>							
							<th>变速箱</th>
							<th>颜色</th>
							<th>排量</th>
							<th>状态</th>
							<th>上传日期</th>
							<th>门店</th>
							<th>负责人</th>
							<th>操作</th>
						</tr>
					</thead> 
					<tbody>
						@foreach ($wants as $want)
    					<tr>
							<td>{{$want->want_code}}</td>
							<td>{{$want->name}}</td>
							<td>{{$want->bottom_price}}-{{$want->top_price}}万</td>							
							<!-- <td>1年</td> -->
							<td>{{$mileage_config[$want->mileage]}}</td>							
							<td>@if($want->gearbox == 0) 不限 @else {{$gearbox[$want->gearbox]}} @endif</td>			
							<td>{{$out_color[$want->out_color]}}</td>						
							<td>@if($want->capacity == 0) 不限 @else {{$capacity[$want->capacity]}} @endif</td>
							<td>{{$want_stauts_config[$want->want_status]}}</td>							
							<td>{{substr($want->created_at, 0 ,10)}}</td>							
							<td>{{$want->belongsToShop->shop_name}}</td>							
							<td>{{$want->belongsToUser->nick_name or ''}}</td>							
														
							<td class="center">
								@if($want->want_status == 1)
								<div class="btn-group">
									<span>
										<form action="{{route('admin.chance.create')}}" method="post" style="display: inherit;margin:0px;">
										    {{ csrf_field() }}
            								<input type="hidden" name="want_id" value="{{$want->id}}">
            								<input type="hidden" name="is_self" value="1">
											<button class="btn btn-success" type="submit">
											<i class="icon-edit icon-white"></i> 匹配
											</button>
										</form>
									</span>
									<!-- <a class="btn btn-warning" href="{{route('admin.want.show', ['want'=>$want->id])}}">
										<i class="icon-edit icon-white"></i> 详细信息
									</a> -->
								</div>
								<input id="current_want_id" type="hidden" value="{{$want->id}}">
								@else
								<div class="btn-group">
									<a class="btn btn-warning" href="{{route('admin.want.show', ['want'=>$want->id])}}">
										<i class="icon-edit icon-white"></i> 查看
									</a>
								</div>
								@endif
							</td>
						</tr>
						@endforeach							
					</tbody>
				</table>
				<div class="pagination pagination-centered">
					 {!! $wants->links() !!}
				</div> 		
			</div>			
		</div>
	</div>
	<div class="modal hide fade" id="myModal">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h3>求购信息搜索</h3>
		</div>
		<div class="modal-body">
			<form class="form-horizontal" id="condition" action="{{route('admin.want.index')}}/index" method="post">
				{!! csrf_field() !!}
				<fieldset>
					<div class="control-group">
						<label class="control-label" for="want_code">求购信息编号</label>
						<div class="controls">
						  	<input class="input-xlarge focused" name="want_code" id="want_code" type="text" value="">
						</div>
					</div>		
					<div class="control-group  ">
            	    	<label class="control-label" for="category_type">车辆类型</label>
            	    	<div class="controls">
            	      		<select id="category_type" name="category_type" >
            	      		<option value=''>不限</option>
            	      			@foreach($category_type as $key=>$category)
            	      			<option @if(isset($select_conditions['category_type']) && $select_conditions['category_type'] == $key && $select_conditions['category_type'] != '') selected @endif value='{{$key}}'>{{$category}}</option>  
            	      			@endforeach                                         
            	      		</select>
            	    	</div>
            	  	</div>
            	  	<div class="control-group  ">
            	    	<label class="control-label" for="shop_id">所属门店</label>
            	    	<div class="controls">
            	      		<select id="shop_id" name="shop_id" >
            	      			<option value="0">所有门店</option>
            	      			@foreach($shops as $key=>$shop)
            	      			<option @if(isset($select_conditions['shop_id']) && $select_conditions['shop_id'] == $shop->id && $select_conditions['shop_id'] != '') selected @endif value='{{$shop->id}}'>{{$shop->name}}</option>  
            	      			@endforeach                                         
            	      		</select>
            	    	</div>
            	  	</div>
            	  	<div class="control-group">
					<label class="control-label" for="selectError3">车型品牌</label>
						<div class="controls">
						  	<select id="top_category" name="brand_id" style="width:25%">
						  		<option value="0">请选择品牌</option>
						  		@foreach ($all_top_brands as $brand)	
						  		<option value="{{$brand->id}}">{{$brand->name}}</option>
						  		@endforeach										
							</select>
							<select id="second_category" name="car_factory" style="display:none;width:25%;">
						  		<option value="0">请选择厂家</option>											
							</select>
							<select id="thrid_category" name="category_id" style="display:none;width:25%;">
						  		<option  value="0">请选择车系</option>											
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="bottom_price">价格范围</label>
						<div class="controls">
						  	<input class="input-xlarge one_line focused" name="bottom_price" id="bottom_price" type="text" value="{{$select_conditions['bottom_price'] or ''}}" placeholder="低价">
						  	<input class="input-xlarge one_line focused" name="top_price" id="top_price" type="text" value="{{$select_conditions['top_price'] or ''}}" placeholder="高价">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="begin_date">日期范围</label>
						<div class="controls">
							<input type="text" class="input-xlarge date-picker one_line" name="begin_date" id="begin_date" value="{{$select_conditions['begin_date'] or ''}}" placeholder="开始日期" >
							<input type="text" class="input-xlarge one_line date-picker" name="end_date" id="end_date" value="{{$select_conditions['end_date'] or ''}}" placeholder="结束日期">
						</div>
					</div>
					<div class="control-group  ">
            	    	<label class="control-label" for="age">车龄</label>
            	    	<div class="controls">
            	      		<select id="age" name="age" >
            	      			@foreach($age as $key=>$ag)
            	      			<option @if(isset($select_conditions['age']) && $select_conditions['age'] == $key && $select_conditions['age'] != '') selected @endif value='{{$key}}'>{{$ag}}</option>  
            	      			@endforeach                                         
            	      		</select>
            	    	</div>
            	  	</div>
            	  	<div class="control-group  ">
            	    	<label class="control-label" for="mileage">里程</label>
            	    	<div class="controls">
            	      		<select id="mileage" name="mileage" >
            	      			@foreach($mileage_config as $key=>$mile)
            	      			<option @if(isset($select_conditions['mileage']) && $select_conditions['mileage'] == $key && $select_conditions['mileage'] != '') selected @endif value='{{$key}}'>{{$mile}}</option>  
            	      			@endforeach                                         
            	      		</select>
            	    	</div>
            	  	</div>
					<!-- <div class="control-group  ">
            	    	<label class="control-label" for="gearbox">变速箱</label>
            	    	<div class="controls">
            	      		<select id="gearbox" name="gearbox" >
            	      			@foreach($gearbox as $key=>$box)
            	      			<option @if(isset($select_conditions['gearbox']) && $select_conditions['gearbox'] == $key && $select_conditions['gearbox'] != '') selected @endif value='{{$key}}'>{{$box}}</option>  
            	      			@endforeach                                         
            	      		</select>
            	    	</div>
            	  	</div> -->
            	  	<div class="control-group">
            	    	<label class="control-label" for="gearbox">变速箱</label>
            	    	<div class="controls">
            	    		
            	    		<label class="checkbox-inline">
            	    			@foreach($gearbox as $key=>$box)
            	    			@if($key > 0)
									<input type="checkbox" name="gearbox[]" value="{{$key}}" @if(isset($select_conditions['gearbox']) && in_array($key, $select_conditions['gearbox'])) checked @endif>{{$box}}
								@endif
								@endforeach
							</label>
							
            	    	</div>
            	  	</div>
            	  	<div class="control-group  ">
            	    	<label class="control-label" for="out_color">外观</label>
            	    	<div class="controls">
            	      		<select id="out_color" name="out_color" >
            	      			@foreach($out_color as $key=>$color)
            	      			<option @if(isset($select_conditions['out_color']) && $select_conditions['out_color'] == $key && $select_conditions['out_color'] != '') selected @endif value='{{$key}}'>{{$color}}</option>  
            	      			@endforeach                                         
            	      		</select>
            	    	</div>
            	  	</div>
            	  	<div class="control-group  ">
            	    	<label class="control-label" for="capacity">排量</label>
            	    	<div class="controls">
            	      		<select id="capacity" name="capacity" >
            	      			@foreach($capacity as $key=>$value)
            	      			<option @if(isset($select_conditions['capacity']) && $select_conditions['capacity'] == $key && $select_conditions['capacity'] != '') selected @endif value='{{$key}}'>{{$value}}</option>  
            	      			@endforeach                                         
            	      		</select>
            	    	</div>
            	  	</div>
            	  	<div class="control-group  ">
            	    	<label class="control-label" for="sale_number">过户次数</label>
            	    	<div class="controls">
            	      		<select id="sale_number" name="sale_number" >
            	      			@foreach($sale_number_config as $key=>$number)
            	      			<option @if(isset($select_conditions['sale_number']) && $select_conditions['sale_number'] == $key && $select_conditions['sale_number'] != '') selected @endif value='{{$key}}'>{{$number}}</option>  
            	      			@endforeach                                         
            	      		</select>
            	    	</div>
            	  	</div>				  
				</fieldset>
				<div class="modal-footer">
			<a href="javascript:void(0);" class="btn" data-dismiss="modal">关闭</a>
			<input type="hidden" name="ajax_request_url" value="{{route('admin.brand.getChildBrand')}}">
			<button type="submit" class="btn btn-primary">搜索</button>
		</div>
			</form>				         
		</div>
		
	</div>
@endsection

@section('script_content')
<!-- 引入车型级联js -->
<script src="{{URL::asset('js/tcl/category.js')}}"></script> 
<!-- 引入日历插件 -->
<script src="{{URL::asset('js/tcl/bootstrap-datepicker.js')}}"></script> 
<script src="{{URL::asset('js/tcl/locales/bootstrap-datepicker.zh-CN.js')}}"></script>
<!-- 引入确认框js -->
<script src="{{URL::asset('js/tcl/confirm.js')}}"></script> 
<script>
	$(document).ready(function(){

		var want_status_current = '{{$want_status_current}}';
		var current_want_id     = $('#current_want_id').val();
		var select_category_id = "{{$select_conditions['category_id'] or '0'}}";
		var select_factory_id  = "{{$select_conditions['car_factory'] or '0'}}";

		$('#second_category').children().first().val(select_factory_id);
		$('#thrid_category').children().first().val(select_category_id);

		/*if(want_status_current == ''){

			$('.select_want_status').each(function(){

				$(this).removeClass('active');
			});
		}*/

		// 废弃-激活
		$('.changStatus').click(function(){

			var status = $(this).attr('data-status');
			/*alert(id);
			alert(status);*/
			// alert($("input[name='_token']").val());

			$.ajax({
				
				type: 'POST',
				url: 'want/changeStatus',
				data: { id : current_want_id, status : status},
				dataType: 'json',
				headers: {

					'X-CSRF-TOKEN': '{{ csrf_token() }}'
				},
				success: function(data){

					alert(data.msg);
					$('#condition').attr('action', '{{route('admin.want.self')}}');
					$('#condition').submit();
					// location.reload();
					// console.log(data);
				},
				error: function(xhr, type){

					alert('操作失败，请重新操作或联系管理员');
				}
			});
		});

		/*$('li.select_want_status').click(function(){

			var want_status = $(this).children('a').attr('data-status');

			if(!$(this).hasClass('active')){

				$(this).addClass('active').siblings().removeClass('active');
				$("select[name='want_status']").val(want_status);

				$('#condition').submit();
			}
			
		});*/

		$('.pagination').children('li').children('a').click(function(){

			// alert($(this).attr('href'));
			$('#condition').attr('action', $(this).attr('href'));
			// alert($('#condition').attr('action'));
			$('#condition').submit();
			return false;
		});

		// 快速跟进
		$('#follow_quickly').click(function(){

			$.ajax({
				
				type: 'POST',
				url: 'want/follwQuickly',
				data: { id : current_want_id},
				dataType: 'json',
				headers: {

					'X-CSRF-TOKEN': '{{ csrf_token() }}'
				},
				success: function(data){

					alert(data.msg);
					// location.reload();
					// console.log(data);
				},
				error: function(xhr, type){

					alert('操作失败，请重新操作或联系管理员');
				}
			});
		});

		$('.date-picker').datepicker({
            language: 'zh-CN',
            autoclose: true,
            format: 'yyyy-mm-dd',
            todayHighlight: true
        });

        $('#top_category').children('option').each(function(){

        	var select_brand_id = "{{$select_conditions['brand_id'] or '0'}}";

        	if($(this).val() == select_brand_id){
        		$(this).attr('selected', 'selected');
        	}
        }); 
	});
</script>
@endsection
