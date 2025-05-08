@extends('layouts.main') 
@section('title', 'Location')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush
<div class="container-fluid">
    <!-- start message area-->
    @include('include.message')
    <!-- end message area-->
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-border">
                <form class="forms-sample" method="POST" action="{{ route('setup-location-save') }}" >
                    <div class="card-header">
                        <div class="col-sm-5 header-title">
                            <h3>{{ __('Location')}}</h3>
                        </div>
                        <div class="col-7 header-btns">
                            <button  id="save-button" class="btn btn-success" id ="saveBtn">{{ __('Save')}}</button>
                            <a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
                        </div>
                    </div>
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="form-group col-sm-2">
                                <label for="vcode" class="col-form-label">{{ __('Code')}}</label>
                                <input name="vcode" id="vcode" type="text" class="form-control input-sm" width="276" value="{{ old('vcode') }}" placeholder="Code"  required/>
                                <div class="help-block with-errors"></div>
                                @error('vcode')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="vname" class="col-form-label">{{ __('Title')}}</label>
                                <input name="vname" id="vname" type="text" class="form-control input-sm" width="276" value="{{ old('vname') }}" placeholder="Title"  required />
                                <div class="help-block with-errors"></div>
                                @error('vname')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-5">
                                <label for="address" class=" col-form-label">{{ __('Address')}}</label>
                                <input name="address" id="address" type="text" class="form-control input-sm" width="276" value="{{ old('address') }}" placeholder="Address"  required/>
                                <div class="help-block with-errors"></div>
                                @error('address')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-2">
                                <label for="state" class=" col-form-label">{{ __('State')}}</label>
                                <input name="state" id="state" type="text" class="form-control input-sm" width="276" value="{{ old('state') }}" placeholder="State"  required/>
                                <div class="help-block with-errors"></div>
                                @error('state')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-2">
                                <label for="zip" class="col-form-label">{{ __('Zip')}}</label>
                                <input name="zip" id="zip" type="text" class="form-control input-sm" width="276" value="{{ old('zip') }}" placeholder="Zip"  required/>
                                <div class="help-block with-errors"></div>
                                @error('zip')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="country" class="col-form-label">{{ __('Country')}}</label>
                                {!! Form::select('countryid', $countries, null,[ 'class'=>'form-control select2', 'id'=> 'countryid','placeholder'=>'--- Select ---', 'required'=> 'required']) !!}
                                <div class="help-block with-errors"></div>
                                @error('countryid')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-3">
                                <label for="telephone" class="col-form-label">{{ __('Telephone')}}</label>
                                <input name="telephone" id="telephone" type="text" class="form-control input-sm" width="276" value="{{ old('telephone') }}" placeholder="Telephone" required />
                                <div class="help-block with-errors"></div>
                                @error('telephone')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-2">
                                <label for="tollfree" class="col-form-label">{{ __('TollFree')}}</label>
                                <input name="tollfree" id="tollfree" type="text" class="form-control input-sm" width="276" value="{{ old('tollfree') }}" placeholder="TollFree" required  />
                                <div class="help-block with-errors"></div>
                                @error('tollfree')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group isactiveSec col-sm-2">
                                <label for="isactive" class=" col-form-label">{{ __('Is Active')}}</label>
                                <span class="form-control input-sm input-checkbox">
                                <input name="isactive" id="isactive" type="checkbox" class="" value="1"  checked />
                                </span>
                                <div class="help-block with-errors"></div>
                                @error('isactive')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <input name="id" id="id" type="hidden" value="{{old('id')}}"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="card p-3 card-border">
                <div class="card-body table-responsive-sm">
                    <table id="locationTable" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Code')}}</th>
                                <th>{{ __('Title')}}</th>
                                <th>{{ __('Address')}}</th>
                                <th>{{ __('State')}}</th>
                                <th>{{ __('Zip')}}</th>
                                <th>{{ __('Country')}}</th>
                                <th>{{ __('Telephone')}}</th>
                                <th>{{ __('TollFree')}}</th>
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
<script src="{{ asset('js/hrpayroll/setup/location.js') }}"></script> 
@endpush
@endsection
