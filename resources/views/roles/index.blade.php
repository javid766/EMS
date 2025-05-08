@extends('layouts.main') 
@section('title', 'Roles')
@section('content')
<!-- push external head elements to head -->
@push('head')
<link rel="stylesheet" href="{{ asset('plugins/DataTables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('/css/treeview.css') }}">
<style type="text/css">
.assign-permissions{
	margin-top: 0.9rem;
	font-weight: 600;
	font-size: 15px;
}
.pad-left{
	padding-left: 2.5rem;
}
.badge-dark {
    color: black;
    border: 1px solid #c4c4c4;
    background: none !important;
}
.view-all{
	color: #000;
	font-size: 12.5px;
	font-weight: 700;	
}
</style>
@endpush
<div class="container-fluid">
	<div class="row">
		<!-- start message area-->
		@include('include.message')
		<!-- end message area-->
		<!-- only those have manage_role permission will get access -->
		
		<div class="col-md-12">
			<div class="card  card-border">
				<form class="forms-sample" method="POST" action="{{url('role/save')}}">
					<div class="card-header header-title">
						<div class="col-sm-5 pl-lg-0">
							<h3>{{ __('Add Role')}}</h3>
						</div>
						<div class="offset-2 col-sm-5 header-btns">
							<button  id="save-button" class="btn btn-success" id ="saveBtn">{{ __('Save')}}</button>
							<a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
						</div>
					</div>
					<div class="card-body">
						@csrf
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group row">
									<label for="role" class="col-sm-1 col-form-label">{{ __('Role')}}</label>
									<div class="col-sm-6 pr-lg-0 pl-lg-0">
										<input id="role" name="role" type="text" class="form-control is-valid input-sm" width="476" placeholder="Role Name" required />
									</div>
									<label for="isActive" class="col-sm-1 col-form-label">{{ __('Is Active')}}</label>

									<div class="col-sm-2">
										<input id="isactive" name="isactive" type="checkbox" class=" align-checkbox mt-2" checked />
									</div>

									<div class="help-block with-errors"></div>
									@error('role')
									<span class="invalid-feedback" role="alert">
										<strong>{{ $message }}</strong>
									</span>
									@enderror
								</div>
								<div>
									<label class="assign-permissions" for="exampleInputEmail3">{{ __('Assign Permission')}} </label>
									<div class="row">
										<div class="col-sm-4 pad-left">
											<label class="custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="select-all-checkboxes" name="select-all-checkboxes" value="1">
												<p class="custom-control-label">
													<!-- clean unescaped data is to avoid potential XSS risk -->
													Select All
												</p>
											</label>
										</div>
									</div>
									
									<div class="row">
										<ul id="coaTree">
											@foreach($webpermissions as $key => $category)
												<li>
													<a id ="editBtn" href="#" class="parent" parentid={{$category->parentid}} >
													{{ $category->name}}</a>
													<input type="checkbox" id="item_checkbox" name="webpermissions[]" value="{{$category->id}}">
													@if(count($category->childs))
													@include('/roles/manage_roles_child',['childs' => $category->childs])
													@endif
												</li>
											@endforeach 
										</ul>
									</div>
								</div>
							</div>
						</div>
					</div>
					<input name="id" id="id" type="hidden" value=""/>
				</form>
			</div>
		</div>
		
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="card p-3 card-border">
				<div class="card-body">
					<table id="roles_table" class="table">
						<thead>
							<tr>
								<th>{{ __('Role')}}</th>
								<th>{{ __('Permissions')}}</th>
								<th>{{ __('Action')}}</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- push external js -->
@push('script')
<script type="text/javascript">
	$("#select-all-checkboxes").click(function(){
		$('input:checkbox:not(#isactive)').not(this).prop('checked', this.checked);
	});
</script>
<script type="text/javascript">
	//Delete Button
	$(document).on('click','#deleteRecord' ,function(){
		var id = $('#id').val();	
		window.location.href = 'role/delete/'+id;
	});
</script>
<!--server side roles table script-->
<script src="{{ asset('js/syscustom.js') }}"></script>
<script src="{{ asset('js/treeview.js') }}"></script>
<script type="text/javascript">
	$('li :checkbox').on('click', function () {
    var $chk = $(this),
        $li = $chk.closest('li'),
        $ul, $parent;
    if ($li.has('ul')) {
        $li.find(':checkbox').not(this).prop('checked', this.checked)
    }
    do {
        $ul = $li.parent();
        $parent = $ul.siblings(':checkbox');
        if ($chk.is(':checked')) {
            $parent.prop('checked', true)
        } else {
            $parent.prop('checked', true)
        }
        $chk = $parent;
        $li = $chk.closest('li');
    } while ($ul.is(':not(.someclass)'));
});
</script>
@endpush
@endsection
