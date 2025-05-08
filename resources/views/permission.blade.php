@extends('layouts.main') 
@section('title', 'Permission')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush

<div class="container-fluid">
    <div class="row">
        <!-- start message area-->
        @include('include.message')
        <!-- end message area-->
        <!-- only those have manage_permission permission will get access -->
        <div class="offset-sm-3 col-sm-6">
            <div class="card card-border">
                <form class="forms-sample" method="POST" action="{{url('permission/create')}}">
                    <div class="card-header">
                        <div class="col-sm-5 header-title">
                            <h3>{{ __('Add Permission')}}</h3>
                        </div>
                        <div class="offset-sm-1 col-sm-6 header-btns">
                            <div class="form-group">
                                <button  id="save-button" type="submit" class="btn btn-success">{{ __('Save')}}</button>
                                <button type="submit" class="btn btn-warning" id="cancel-button" >{{ __('Cancel')}}</button> 
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="forms-sample" method="POST" action="{{url('permission/create')}}">
                            @csrf
                            <div class="form-group row">
                                <label for="permission" class="col-sm-3 col-form-label">{{ __('Permission')}}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="permission" name="permission" placeholder="Permission Name" value="{{ isset($permission_name) }}" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="exampleInputEmail3" class="col-sm-3 col-form-label">{{ __('Assigned to Role')}} </label>
                                <div class="col-sm-9">
                                    {!! Form::select('roles[]', $roles, null,[ 'class'=>'form-control select2', 'multiple' => 'multiple', 'id' => 'assigned_role']) !!}
                                </div>  
                            </div>     
                        </div>
                        <input name="id" id="id" type="hidden" value=""/>
                    </form>
                </div>
            </div>  
        </div>
        <div class="row">
            <div class="offset-sm-3 col-sm-6">
                <div class="card p-3">
                    <div class="card-body">
                        <table id="permission_table" class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('Permission')}}</th>
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

    <!--server side permission table script-->
    <script src="{{ asset('js/permission.js') }}"></script>

    @endpush
    @endsection
