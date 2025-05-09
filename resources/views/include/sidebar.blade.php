<div class="app-sidebar colored">
	<div class="sidebar-header">
		<a class="header-brand" href="{{route('dashboard')}}">
			<div class="logo-img">
				<img height="30" src="{{ asset('img/logo/EMS-sidebar-logo.png')}}" class="header-brand-img" title="EMS"> 
			</div>
		</a>
		<div class="sidebar-action"><i class="ik ik-arrow-left-circle"></i><button id="sidebarClose" class="nav-close"><i class="ik ik-x"></i></button></div>
	</div>

	@php
	$segment1 = request()->segment(1);
	$segment2 = request()->segment(2);
	$segment3 = request()->segment(3);
	$helperPermission = new App\Helper\Permission();
	@endphp
	
	<div class="sidebar-content">
		<div class="nav-container">
			<nav id="main-menu-navigation" class="navigation-main">
				@can($helperPermission->MANAGE_DASHBOARD)
				<div class="nav-item {{ ($segment1 == 'dashboard') ? 'active' : '' }}">
					<a href="{{route('dashboard')}}"><i class="ik ik-bar-chart-2"></i><span>{{ __('Dashboard')}}</span></a>
				</div>
				@endcan
				@can($helperPermission->MANAGE_ADMINSTRATOR)
				<div class="nav-item {{ ($segment1 == 'users' || $segment2 == 'tenant' || $segment1 == 'roles'||$segment1 == 'permission' ||$segment1 == 'user' || $segment2 == 'change-password' || $segment1 == 'configurations') ? 'active open' : '' }} has-sub">
					<a href="#"><i class="ik ik-user"></i><span>{{ __('Administration')}}</span></a>
					<div class="submenu-content">
						<!-- only those have manage_user permission will get access -->
						@can($helperPermission->MANAGE_USER)
						<a href="{{url('users')}}" class="menu-item {{ ($segment1 == 'users') ? 'active' : '' }}">{{ __('Users')}}</a>
						@endcan
						@can($helperPermission->MANAGE_PASSWORD)
						<a href="{{route('change-password')}}" class="menu-item {{ ($segment1 == 'user' && $segment2 == 'change-password') ? 'active' : '' }}">{{ __('Change Password')}}</a>
						@endcan
						<!-- only those have manage_role permission will get access -->
						@can($helperPermission->MANAGE_ROLE)
						<a href="{{url('roles')}}" class="menu-item {{ ($segment1 == 'roles') ? 'active' : '' }}">{{ __('Roles')}}</a>
						@endcan
						@can($helperPermission->MANAGE_CONFIGS) 
						<a href="{{route('configurations')}}" class="menu-item {{ ($segment1 == 'configurations') ? 'active' : '' }}">{{ __('Configurations')}}</a>
						@endcan

					</div>			
				</div>
				@endcan
				@can($helperPermission->MANAGE_SETUP)
				<div class="nav-item  {{ ($segment2 == 'company' || $segment2 == 'currency'|| $segment2 == 'currency-exchange'  || $segment2 == 'country' ||$segment2 == 'project' ||$segment2 == 'bank' || $segment2 == 'financial-year'|| $segment2 == 'location'|| $segment2 == 'grade' || $segment2 == 'dept'|| $segment2 == 'dept-group' ||$segment2 == 'desg' ||$segment2 == 'religion' || $segment2 == 'job-status'|| $segment2 == 'left-status'|| $segment2 == 'gender'|| $segment2 == 'etype' || $segment2 == 'entitlement-type'|| $segment2 == 'weekday'|| $segment2 == 'ramazan'|| $segment2 == 'allowded'|| $segment2 == 'allowed-group'|| $segment2 == 'attcode'|| $segment2 == 'holiday'|| $segment2 == 'shift' || $segment2 == 'roster-shift' || $segment2 == 'salary-tax-slab' || $segment2 == 'sale-types' || $segment2 == 'loan-types' || $segment2 == 'setup-report' || $segment2 == 'leave-balance' || $segment2 == 'probation-status') ? 'active open' : '' }}  has-sub">
					<a href="#"><i class="ik ik-file"></i><span>{{ __('Setup')}}</span></a>
					<div class="submenu-content">
						<!-- only those have manage_user permission will get access -->
						@can($helperPermission->MANAGE_COMPANY)
						<a href="{{route('setup-company')}}" class="menu-item {{ ($segment2 == 'company' ) ? 'active' : '' }}">{{ __('Company')}}</a>  
						@endcan
						@can($helperPermission->MANAGE_CURRENCY)                   
						<a href="{{route('setup-currency')}}" class="menu-item {{ ($segment1 == 'setup' && $segment2 == 'currency' ) ? 'active' : '' }}">{{ __('Currency')}}</a>
						@endcan
						@can($helperPermission->MANAGE_COUNTRY) 
						<a href="{{route('setup-country')}}" class="menu-item {{ ($segment1 == 'setup' && $segment2 == 'country' ) ? 'active' : '' }}">{{ __('Country')}}</a>
						@endcan
						@can($helperPermission->MANAGE_FINANCIAL_YEAR)
						<a href="{{route('setup-financial-year')}}" class="menu-item {{ ($segment1 == 'setup' && $segment2 == 'financial-year' ) ? 'active' : '' }}">{{ __('Financial Year')}}</a>    
						@endcan							
						@can($helperPermission->MANAGE_LOCATION)
						<a href="{{route('setup-location')}}" class="menu-item {{ ($segment1 == 'setup' && $segment2 == 'location' ) ? 'active' : '' }}">{{ __('Location')}}</a>
						@endcan

						@can($helperPermission->MANAGE_ATT_DEPT_GROUP)                 
						<a href="{{route('setup-dept-group')}}" class="menu-item {{ ($segment1 == 'setup' && $segment2 == 'dept-group' ) ? 'active' : '' }}">{{ __('Department Group')}}</a>
						@endcan
						@can($helperPermission->MANAGE_ATT_DEPT)
						<a href="{{route('setup-dept')}}" class="menu-item {{ ($segment1 == 'setup' && $segment2 == 'dept' ) ? 'active' : '' }}">{{ __('Department')}}</a> 
						@endcan					
						@can($helperPermission->MANAGE_ATT_DESG)
						<a href="{{route('setup-desg')}}" class="menu-item {{ ($segment1 == 'setup' && $segment2 == 'desg' ) ? 'active' : '' }}">{{ __('Designation')}}</a>
						@endcan
						@can($helperPermission->MANAGE_ATT_GRADE)
						<a href="{{route('setup-grade')}}" class="menu-item {{ ($segment1 == 'setup' && $segment2 == 'grade' ) ? 'active' : '' }}">{{ __('Grades')}}</a>
						@endcan
						
						@can($helperPermission->MANAGE_ATT_RELIGION)
						<a href="{{route('setup-religion')}}" class="menu-item {{ ($segment1 == 'setup' && $segment2 == 'religion' ) ? 'active' : '' }}">{{ __('Religion')}}</a>  
						@endcan
			
						@can($helperPermission->MANAGE_ATT_ENTITLEMENT_TYPE)
						<a href="{{route('setup-entitlement-type')}}" class="menu-item {{ ($segment1 == 'setup' && $segment2 == 'entitlement-type' ) ? 'active' : '' }}">{{ __('Entitlement Type')}}</a>
						@endcan
						
						@can($helperPermission->MANAGE_ATT_ATTCODE)
						<a href="{{route('setup-attcode')}}" class="menu-item {{ ($segment1 == 'setup' && $segment2 == 'attcode' ) ? 'active' : '' }}">{{ __('AttCode')}}</a>
						@endcan				
						@can($helperPermission->MANAGE_ATT_SHIFT)
						<a href="{{route('setup-shift')}}" class="menu-item {{ ($segment1 == 'setup' && $segment2 == 'shift' ) ? 'active' : '' }}">{{ __('Shift')}}</a>
						@endcan
						@can($helperPermission->MANAGE_ATT_ROSTER_SHIFT)
						<a href="{{route('setup-roster-shift')}}" class="menu-item {{ ($segment1 == 'setup' && $segment2 == 'roster-shift' ) ? 'active' : '' }}">{{ __('Shift Roster')}}</a>
						@endcan
						@can($helperPermission->MANAGE_ATT_HOLIDAY)
						<a href="{{route('setup-holiday')}}" class="menu-item {{ ($segment1 == 'setup' && $segment2 == 'holiday' ) ? 'active' : '' }}">{{ __('Holiday')}}</a>
						@endcan
						@can($helperPermission->MANAGE_ATT_LEAVE)
						<a href="{{route('setup-leave-balance')}}" class="menu-item {{ ($segment1 == 'setup' && $segment2 == 'leave-balance' ) ? 'active' : '' }}">{{ __('Leave Balance')}}</a>
						@endcan
						@can($helperPermission->MANAGE_BANK) 
						<a href="{{route('setup-bank')}}" class="menu-item {{ ($segment2 == 'bank' ) ? 'active' : '' }}">{{ __('Salary Banks')}}</a>
						@endcan
						
						@can($helperPermission->MANAGE_SAL_TAX_SLAB) 
						<a href="{{route('salary-tax-slab')}}" class="menu-item {{ ($segment1 == 'setup' && $segment2 == 'salary-tax-slab' ) ? 'active' : '' }}">{{ __('Salary Tax Slab')}}</a>
						@endcan	
						@can($helperPermission->MANAGE_LOAN_TYPES) 
						<a href="{{route('loan-types')}}" class="menu-item {{ ($segment2 == 'loan-types' ) ? 'active' : '' }}">{{ __('Loan Types')}}</a>
						@endcan	
						@can($helperPermission->MANAGE_SALE_TYPES) 
						<a href="{{route('sale-types')}}" class="menu-item {{ ($segment2 == 'sale-types' ) ? 'active' : '' }}">{{ __('Local Sale Types')}}</a>
						@endcan	
						@can($helperPermission->MANAGE_SETUP_REPORTS) 
						<a href="{{route('setup-report')}}" class="menu-item  {{ ($segment2 == 'setup-report' ) ? 'active' : '' }}">{{ __('Setup Reports')}}</a>
						@endcan					
					</div>
				</div>
				@endcan
				@can($helperPermission->MANAGE_EMP)
				<div class="nav-item  has-sub {{ ($segment1 == 'employee') ? 'active open' : '' }}  ">
					<a href="#"><i class="ik ik-users"></i><span>{{ __('Employee')}}</span></a>
					<div class="submenu-content">
						<!-- only those have manage_user permission will get access -->
						@can($helperPermission->MANAGE_EMP_INFO)   					
						<a href="{{route('employeeinfo')}}" class="menu-item  {{ ($segment2 == 'employeeinfo' ) ? 'active' : '' }}">{{ __('Employee Main')}}</a>
						@endcan
						@can($helperPermission->MANAGE_EMP_CARD_PRINTING) 
						<a href="{{route('employee-card-printing')}}" class="menu-item {{ ($segment2 == 'card-printing' ) ? 'active' : '' }}">{{ __('Card Printing')}}</a>
						@endcan
						@can($helperPermission->MANAGE_EMP_LOC_TRANSFER) 
						<a href="{{route('employee-transfer')}}" class="menu-item {{ ($segment2 == 'employeetransfer' ) ? 'active' : '' }}">{{ __('Employee Transfer')}}</a>	
						@endcan	
						@can($helperPermission->MANAGE_EMP_FIX_TAX) 				
						<a href="{{route('employee-fix-tax')}}" class="menu-item {{ ($segment2 == 'fix-tax' ) ? 'active' : '' }}">{{ __('Employee FixTax')}}</a>
						@endcan
						@can($helperPermission->MANAGE_EMP_CLOSING_MONTH_CHEQUE) 
						<a href="{{route('employee-closing-month-cheque')}}" class="menu-item {{ ($segment2 == 'closing-month-cheque' ) ? 'active' : '' }}">{{ __('Closing Month Cheque')}}</a>	
						@endcan
						@can($helperPermission->MANAGE_EMP_Advance) 									
						<a href="{{route('employee-advance')}}" class="menu-item {{ ($segment2 == 'advance' ) ? 'active' : '' }}">{{ __(' Advance')}}</a>
						@endcan
						@can($helperPermission->MANAGE_EMP_LOCAL_SALE) 
						<a href="{{route('employee-local-sale')}}" class="menu-item {{ ($segment2 == 'local-sale' ) ? 'active' : '' }}">{{ __('Local Sale')}}</a>
						@endcan
						@can($helperPermission->MANAGE_EMP_Loan) 						
						<a href="{{route('employee-loan')}}" class="menu-item {{ ($segment2 == 'loan' ) ? 'active' : '' }}">{{ __('Loan')}}</a>  
						@endcan                         
						@can($helperPermission->MANAGE_LOAN_DEDUCTION) 
						<a href="{{route('employee-loan-deduction')}}" class="menu-item {{ ($segment2 == 'loan-deduction' ) ? 'active' : '' }}">{{ __('Loan Deduction')}}</a>
						@endcan
						@can($helperPermission->MANAGE_EMP_REPORT) 
						<a href="{{route('employee-report')}}" class="menu-item {{ ($segment2 == 'report' ) ? 'active' : '' }}">{{ __('Employee Report')}}</a>
						@endcan
						@can($helperPermission->MANAGE_EMP_TRIAL) 
						<a href="{{route('trial-employee-entry')}}" class="menu-item {{ ($segment2 == 'trial-employee-entry' ) ? 'active' : '' }}">{{ __('Trial Employee')}}</a>
						@endcan
					</div>		
				</div>
				@endcan	
				@can($helperPermission->MANAGE_TIME_ENTRY)
				<div class="nav-item  has-sub {{ ($segment1 == 'time-entry') ? 'active open' : '' }}">
					<a href="#"><i class="ik ik-clock"></i><span>{{ __('Time Entry')}}</span></a>
					<div class="submenu-content">
						<!-- only those have manage_user permission will get access -->
						@can($helperPermission->MANAGE_TE_Att_ENTRY)   
						<a href="{{route('time-entry-attentry')}}" class="menu-item {{ ($segment2 == 'attendance-entry' ) ? 'active' : '' }}">{{ __('Attendance Entry')}}</a>
						@endcan
						<?php $user_flag = Session::get('user_flag');?>
                         @if($user_flag == 1 || $user_flag == 0)
                         @can($helperPermission->MANAGE_TE_CHANGE_Att_EMPLOYEE)
						<a href="{{route('att-change-attendence')}}" class="menu-item {{ ($segment2 == 'change-attendence' ) ? 'active' : '' }}">{{ __('Change Attendance Employee')}}</a>
						@endcan 					
						@endif
						@can($helperPermission->MANAGE_EMP_MONTH_ATT_DAYS) 
						<a href="{{route('employee-month-days')}}" class="menu-item {{ ($segment2 == 'month-days' ) ? 'active' : '' }}">{{ __('Month Attendance Days')}}</a>
						@endcan
						 @can($helperPermission->MANAGE_TE_DAILY_MANUAL_OT)
						<a href="{{route('daily-manual-ot')}}" class="menu-item {{ ($segment2 == 'daily-manual-ot' ) ? 'active' : '' }}">{{ __('O/T Entry - Daily')}}</a>
						@endcan
						@can($helperPermission->MANAGE_TE_MONTHLY_OT_ENTRY)
						<a href="{{route('ot-entry-monthly')}}" class="menu-item {{ ($segment2 == 'ot-entry-monthly' ) ? 'active' : '' }}">{{ __('O/T Entry - Monthly ')}}</a>
						@endcan
						@can($helperPermission->MANAGE_TE_OFFICIAL_VISIT_ENTRY)
						<a href="{{route('official-visit-entry')}}" class="menu-item {{ ($segment2 == 'official-visit-entry' ) ? 'active' : '' }}">{{ __('Offical Visit Entry')}}</a>
						@endcan
						@can($helperPermission->MANAGE_TE_LEAVE_ENTRY)
						<a href="{{route('leave-entry')}}" class="menu-item {{ ($segment2 == 'leave-entry' ) ? 'active' : '' }}">{{ __('Leave Apply')}}</a>
						@endcan
						@can($helperPermission->MANAGE_TE_LEAVE_APPROVAL)
						<a href="{{route('leave-approval')}}" class="menu-item {{ ($segment2 == 'leave-approval' ) ? 'active' : '' }}">{{ __('Leave Approval')}}</a>
						@endcan
						@can($helperPermission->MANAGE_TE_ROSTER_ENTRY)
						<a href="{{route('roster-entry')}}" class="menu-item {{ ($segment2 == 'roster-entry' ) ? 'active' : '' }}">{{ __('Roster Entry')}}</a>
						@endcan
					</div>				
				</div>
				@endcan
				
				@can($helperPermission->MANAGE_POSTING)
				<div class="nav-item  has-sub  {{ ($segment1 == 'posting') ? 'active open' : '' }}">  
					<a href="#"><i class="ik ik-clipboard"></i><span>{{ __('Posting')}}</span></a>
					<div class="submenu-content">
						<!-- only those have manage_user permission will get access -->
						@can($helperPermission->MANAGE_Att_POSTING)   
						<a href="{{route('daily-posting')}}" class="menu-item {{ ($segment2 == 'daily-posting' ) ? 'active' : '' }}">{{ __('Attendance Posting')}}</a>
						@endcan
						@can($helperPermission->MANAGE_SALARY_POSTING)
						<a href="{{route('salary-posting')}}" class="menu-item {{ ($segment2 == 'salary-posting' ) ? 'active' : '' }}">{{ __('Salary  Posting')}}</a>
						@endcan
					</div>					
				</div>
				@endcan
				@can($helperPermission->MANAGE_REPORTS)
				<div class="nav-item  has-sub   {{ ($segment2 == 'attendance-listing-report' ||  $segment2 == 'monthy-attendence-report' ||  $segment2 == 'monthy-salary-report') ? 'active open' : '' }}">  
					<a href="#"><i class="ik ik-file-text"></i><span>{{ __('Reports')}}
					</span></a>
					<div class="submenu-content">
						<!-- only those have manage_user permission will get access -->
						@can($helperPermission->MANAGE_Att_LISTING_REPORT)   
						<a href="{{route('attendance-listing-report')}}" class="menu-item {{ ($segment2 == 'attendance-listing-report' ) ? 'active' : '' }}">{{ __('Daily Attendance')}}</a>
						@endcan
						@can($helperPermission->MANAGE_Att_MONTHLY_REPORT)
						<a href="{{route('monthy-attendence-report')}}" class="menu-item {{ ($segment2 == 'monthy-attendence-report' ) ? 'active' : '' }}">{{ __('Monthly Attendance')}}</a>
						@endcan
						@can($helperPermission->MANAGE_MONTHLY_SALARY_REPORT)
						<a href="{{route('monthy-salary-report')}}" class="menu-item {{ ($segment2 == 'monthy-salary-report' ) ? 'active' : '' }}">{{ __('Monthly Salary')}}</a>
						@endcan
					</div>				
				</div>
				@endcan
			</div>
		</div>
	</div>