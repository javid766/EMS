@extends('layouts.main')
@section('title', $role->name.' - Edit Role')
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
                <form class="forms-sample" method="POST" action="{{url('role/update')}}">
                    <div class="card-header header-title">
                        <div class="col-sm-5 pl-lg-0">
                            <h3>{{ __('Edit Role')}}</h3>
                        </div>
                        <div class="offset-2 col-sm-5 header-btns">
                            <button type="submit" class="btn btn-success">{{ __('Update')}}</button>
                        </div>
                    </div>
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <input type="hidden" name="id" value="{{$role->id}}" required>
                                    <label for="role" class="col-sm-1 col-form-label">{{ __('Role')}}</label>
                                    <div class="col-sm-6 pr-lg-0 pl-lg-0">
                                        <input id="role" name="role" type="text" class="form-control is-valid input-sm" width="476" placeholder="Role Name" value="{{ clean($role->name, 'titles')}}" required />
                                    </div>
                                    <label for="isActive" class="col-sm-1 col-form-label">{{ __('Is Active')}}</label>

                                    <div class="col-sm-2">
                                        @if($role->isactive == '1')
                                        <input id="isactive" name="isactive" type="checkbox" class=" align-checkbox mt-2" checked />
                                        @else
                                        <input id="isactive" name="isactive" type="checkbox" class=" align-checkbox mt-2" />
                                        @endif
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
                                                    <input type="checkbox" id="item_checkbox" name="webpermissions[]" value="{{$category->id}}"
                                                    @if(in_array($category->id, $role_permission))
                                                    checked
                                                    @endif>
                                                    
                                                    @if(count($category->childs))
                                                    @include('/roles/manage_edit_roles_child',['childs' => $category->childs])
                                                    @endif
                                                </li>
                                            @endforeach 
                                        </ul> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- push external js -->
@push('script')
<script type="text/javascript">
    $("#select-all-checkboxes").click(function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>
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
