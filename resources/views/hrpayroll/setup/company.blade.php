@extends('layouts.main') 
@section('title', 'Company')
@section('content')
<!-- push external head elements to head -->
@push('head')
@endpush
<div class="container-fluid">
    <!-- start message area-->
    @include('include.message')
    <!-- end message area-->
    <div class="row">
        <div class="col-12">
            <div class="card card-border">
                <form enctype="multipart/form-data" class="forms-sample" method="POST" action="{{route('setup-company-save') }}" >
                    <div class="card-header">
                        <div class="header-title col-4">
                            <span>
                                <h3 class="title-heading">{{ __(' Company')}}</h3>
                            </span>
                        </div>
                        <div class="col-8 header-btns">
                            <a  id="search-button" class="btn btn-secondary">{{ __('Search')}}</a>
                            <button  id="save-button" class="btn btn-success">{{ __('Save')}}</button>
                            <a  class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
                        </div>
                    </div>
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="form-group col-lg-4 col-sm-12">
                                <input type="hidden" name="tid" value="1">
                                <input type="hidden" name="shipping_methodid" value="1">
                                <input type="hidden" name="id" id="id" value="{{ old('id') }}">
                                <!--input type="hidden" name="shipping_methodid" value="1"-->
                                <label for="vname" class="col-form-label">{{ __('Company Name ')}}<span class="text-red">*</span></label>
                                <input id="vname" type="text" class="form-control " name="vname" value="{{ old('vname') }}" placeholder="Company Name" required>
                                <div class="help-block with-errors"></div>
                                @error('vname')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="vcode" class="col-form-label">{{ __('Company Code ')}}<span class="text-red">*</span></label>
                                <input id="vcode" type="text" class="form-control " name="vcode" value="{{ old('vcode') }}" placeholder="Company Code " required>
                                <div class="help-block with-errors"></div>
                                @error('vcode')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="company_nature" class="col-form-label">{{ __('Company Nature ')}}<span class="text-red">*</span></label>
                                <input id="company_nature" type="text" class="form-control " name="company_nature" value="{{ old('company_nature') }}" placeholder="Company Nature" required>
                                <div class="help-block with-errors"></div>
                                @error('company_nature')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-12">
                                <label for="url" class="col-form-label">{{ __('Company Url')}}</label>
                                <input id="url" type="text" class="form-control " name="url" value="{{ old('url') }}" placeholder="Company Url" >
                                <div class="help-block with-errors"></div>
                                @error('url')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="display_url" class="col-form-label">{{ __('Display Url ')}}</label>
                                <input id="display_url" type="text" class="form-control " name="display_url" value="{{ old('display_url') }}" placeholder="Display Url" >
                                <div class="help-block with-errors"></div>
                                @error('display_url')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="currency" class="col-form-label">{{ __('Currency ')}}<span class="text-red">*</span></label>
                                <div>
                                    {!! Form::select('currencyid', $currencies, null,[ 'class'=>'form-control select2', 'id'=> 'currencyid', 'required'=> 'required', 'placeholder' =>'--- Select ---']) !!}
                                </div>
                                <div class="help-block with-errors"></div>
                                @error('currency')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-12">
                                <label for="phone" class="col-form-label">{{ __('Phone No')}}</label>
                                <input id="phone" type="tel" class="form-control " name="phone" value="{{ old('phone') }}" placeholder="Phone #" >
                                <div class="help-block with-errors"></div>
                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="uan" class="col-form-label">{{ __('UAN No ')}}</label>
                                <input id="uan" type="tel" class="form-control " name="uan" value="{{ old('uan') }}" placeholder="UAN #" >
                                <div class="help-block with-errors"></div>
                                @error('uan')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="fax" class="col-form-label">{{ __('Fax No')}}</label>
                                <input id="fax" type="tel" class="form-control " name="fax" value="{{ old('fax') }}" placeholder="Fax #" >
                                <div class="help-block with-errors"></div>
                                @error('fax')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-12">
                                <label for="salestaxno" class="col-form-label">{{ __('Sales Tax No/VAT No')}}</label>
                                <input id="salestaxno" type="text" class="form-control " name="salestaxno" value="{{ old('salestaxno') }}" placeholder="Sales Tax #" >
                                <div class="help-block with-errors"></div>
                                @error('salestaxno')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="ntn_heading" class="col-form-label">{{ __('NTN Heading ')}}</label>
                                <input id="ntn_heading" type="text" class="form-control " name="ntn_heading" value="{{ old('ntn_heading') }}" placeholder="NTN Heading" >
                                <div class="help-block with-errors"></div>
                                @error('ntn_heading')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="cnic_heading" class="col-form-label">{{ __('CNIC Heading')}}</label>
                                <input id="cnic_heading" type="text" class="form-control " name="cnic_heading" value="{{ old('cnic_heading') }}" placeholder="CNIC Heading" >
                                <div class="help-block with-errors"></div>
                                @error('cnic_heading')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-12">
                                <label for="registrationno" class="col-form-label">{{ __('Registration No ')}}</label>
                                <input id="registrationno" type="text" class="form-control " name="registrationno" value="{{ old('registrationno') }}" placeholder="Registration No" >
                                <div class="help-block with-errors"></div>
                                @error('registrationno')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="timezone" class="col-form-label">{{ __('Time Zone')}}</label>
                                <input id="timezone" type="text" class="form-control " name="timezone" value="{{ old('timezone') }}" placeholder="Time Zone" >
                                <div class="help-block with-errors"></div>
                                @error('timezone')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="ccity " class="col-form-label">{{ __('Company City')}}</label>
                                <input id="city" type="text" class="form-control " name="city" value="{{ old('city') }}" placeholder="Company City" >
                                <div class="help-block with-errors"></div>
                                @error('city')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-12">
                                <label for="country" class="col-form-label">{{ __('Country')}}<span class="text-red">*</span></label>
                                <div>
                                    {!! Form::select('countryid', $countries, null,[ 'class'=>'form-control select2', 'id' => 'country' ,'required' =>'required' , 'placeholder' => '--- Select ---']) !!}
                                </div>
                                <div class="help-block with-errors"></div>
                                @error('registration')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="address " class="col-form-label">{{ __('Company Address')}}</label>
                                <input id="address" type="text" class="form-control " name="address" value="{{ old('address') }}" placeholder="Company Address" >
                                <div class="help-block with-errors"></div>
                                @error('address')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="compdateformat " class="col-form-label">{{ __('Company Date Format')}}</label>
                                {!! Form::select('compdateformat', $compdateformat, null,[ 'class'=>'form-control select2', 'id' => 'compdateformat']) !!}
                                <div class="help-block with-errors"></div>
                                @error('compdateformat')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-12">
                                <label for="saletaxper" class="col-form-label">{{ __('Sales Tax Per')}}</label>
                                <input id="saletaxper" type="text" class="form-control " name="saletaxper" value="0" placeholder="0"  readonly>
                                <div class="help-block with-errors"></div>
                                @error('saletaxper')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="vatper" class="col-form-label">{{ __('VAT Per')}}</label>
                                <input id="vatper" type="text" class="form-control " name="vatper" value="0" placeholder="0" readonly>
                                <div class="help-block with-errors"></div>
                                @error('vatper')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-lg-2 col-sm-12">
                                <label for="havesaletax" class="col-form-label">Have Sales Tax</label>
                                <span class="form-control input-sm input-checkbox">
                                <input id="havesaletax" type="checkbox" class="" name="havesaletax" value="1" placeholder="" ></span>
                                <div class="help-block with-errors"></div>
                                @error('havesaletax')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group col-lg-2 col-sm-12">
                                <label for="havevat" class="col-form-label">Have VAT</label>
                                <span class="form-control input-sm input-checkbox">
                                <input id="havevat" type="checkbox" class="" name="havevat" value="1" placeholder="">
                                </span>
                                <div class="help-block with-errors"></div>
                                @error('have_vat')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-12">
                                <label for="logo" class="col-form-label">Logo Image</label>
                                <div id="logoImgPreview"></div>
                                <input id="logo" type="file" class="" name="logo" value="" placeholder="" >
                                <div class="help-block with-errors"></div>
                                @error('logo')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div id="logoResult"></div>
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="signature_image" class="col-form-label">Signature Image</label>
                                <div id="signImgPreview"></div>
                                <input id="signature_image" type="file" class="" name="signature_image" value="" placeholder="" >
                                <div class="help-block with-errors"></div>
                                @error('signature_image')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                                <div id="signResult"></div>
                            </div>
                            <div class="form-group col-lg-2 col-sm-12">
                                <label for="isactive" class="col-form-label">Is Active</label>
                                <span class="form-control input-sm input-checkbox">
                                <input class="" name="isactive" id="isactive" type="checkbox" value="1" checked />
                                </span>
                                <div class="help-block with-errors"></div>
                                @error('isactive')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                                </span>
                                @enderror
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
<script src="{{ asset('js/hrpayroll/setup/company.js') }}"></script> 
@endpush
@endsection
