@extends('layouts.main') 
@section('title', 'Local Sale')
@section('content')
<!-- push external head elements to head -->
@push('head')

@endpush
	<!-- start message area-->
	@include('include.message')
	<!-- end message area-->
	<div class="row">
		<div class="col-sm-12">
			<div class="card card-border">
				<form class="forms-sample" method="POST" action="{{url('employee/local-sale/save')}}">
					<div class="card-header">
						<div class="col-sm-5 header-title">
							<h3>{{ __('Local Sale')}}</h3>
						</div>
						<div class="col-7 header-btns">
							<button  id="save-button" class="btn btn-success" id ="saveBtn">{{ __('Save')}}</button>
							<a href="#" class="btn btn-warning" id="cancelBtn" >{{ __('Cancel')}}</a> 
						</div>
					</div>
                    <div class="card-body"> 
                        @csrf

                        <div class="row">                          
                            <div class="form-group col-sm-4">
                                <label for="empid" class="col-form-label">{{ __(' Employee')}}</label>
                                {!! Form::select('employeeid', $employees, null,[ 'class'=>'form-control select2', 'id'=> 'employeeid', 'placeholder'=> '--- Select Employee ---', 'required'=> 'required']) !!}
                            </div> 

                            <div class="form-group col-sm-3">
                                <label for="invoiceno" class="col-form-label">{{ __('Invoice #')}}</label>
                                <input id="invoiceno" type="text" class="form-control" name="invoiceno" value="{{old('invoiceno')}}" placeholder="Invoice #" required>

                            </div>  

                             <div class="form-group col-sm-2">
                                <label for="qty" class="col-form-label">{{ __('QTY')}}</label>
                                <input id="qty" type="number" value="{{old('qty')}}" class="form-control" name="qty" placeholder="0" onkeydown="return event.charCode != 45 && event.keyCode !== 69 && event.keyCode !== 189" required>

                            </div>  

                             <div class="form-group col-sm-3">
                                <label for="amount" class="col-form-label">{{ __('Amount')}}</label>
                                <input id="amount" type="number" class="form-control" name="amount" value="{{old('amount')}}" onkeydown="return event.charCode != 45 && event.keyCode !== 69 && event.keyCode !== 189"  placeholder="00" step="0.01" required>

                            </div>  
                        </div>

                        <div class="row">  

                            <div class="form-group col-sm-2">
                                <label class="col-form-label">Local Sale Type</label>
                                {!! Form::select('saletypeid',$saleTypes, null, ['class'=>'form-control select2', 'id'=> 'saletypeid', 'placeholder' => '--- Select ---' ,'required' => 'required']) !!}
                            </div>

                            <div class="form-group col-sm-2">
                                <label for="vdate" class="col-form-label">{{ __('Date')}}</label>
                                <input name="vdate" id="vdate" value="{{old('vdate')}}" type="date" class="form-control input-sm" width="276" required />
                            </div>

                            <div class="form-group col-sm-5">
                                <label for="remarks" class="col-form-label">{{ __('Remarks')}}</label>

                                <input id="remarks" type="text" class="form-control" name="remarks" value="{{old('remarks')}}" placeholder="Remarks" required>
                            </div>  
                        </div>
                        <input name="id" id="id" type="hidden" value="{{old('id')}}"/>
                    </div>
                </form>
            </div>
        </div>
    </div>

<div class="row">
  <div class="col-sm-12">
     <div class="card p-3 card-border">
        <div class="card-body table-responsive-sm">
           <table id="localSaleTable" class="table table-bordered table-striped table-hover">
              <thead>
                 <tr>           
                    <th>{{ __('Employee')}}</th>
                    <th>{{ __('Date')}}</th>
                    <th>{{ __('Invoice #')}}</th>
                    <th>{{ __('Qty')}}</th>
                    <th>{{ __('Amount')}}</th>
                    <th>{{ __('Sale Type')}}</th>
                    <th>{{ __('Remarks')}}</th>
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

<!-- push external js -->
@push('script') 
    <script src="{{ asset('js/hrpayroll/employee/local_sale.js') }}"></script> 
@endpush
@endsection
