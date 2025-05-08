<?php

use Illuminate\Support\Facades\Route;

use App\Helper\Permission;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\DoubleloginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;

use App\Http\Controllers\HrPayroll\Dashboard\GraphController;
use App\Http\Controllers\HrPayroll\Dashboard\DashboardController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\PermissionController;

use App\Http\Controllers\User\UserCompanyController;
use App\Http\Controllers\User\UserLocationController;
use App\Http\Controllers\User\UserTypeController;

use App\Http\Controllers\HrPayroll\Setup\ConfigurationsController;
use App\Http\Controllers\HrPayroll\Setup\SalaryBanksController;
use App\Http\Controllers\HrPayroll\Setup\TenantController;
use App\Http\Controllers\HrPayroll\Setup\CompanyController;
use App\Http\Controllers\HrPayroll\Setup\CountryController;
use App\Http\Controllers\HrPayroll\Setup\LocationController;
use App\Http\Controllers\HrPayroll\Setup\CurrencyController;
use App\Http\Controllers\HrPayroll\Setup\DesgController;
use App\Http\Controllers\HrPayroll\Setup\GradeController;
use App\Http\Controllers\HrPayroll\Setup\DeptsController;
use App\Http\Controllers\HrPayroll\Setup\GenderController;
use App\Http\Controllers\HrPayroll\Setup\HolidayController;
use App\Http\Controllers\HrPayroll\Setup\AttCodeController;
use App\Http\Controllers\HrPayroll\Setup\WeekdayController;
use App\Http\Controllers\HrPayroll\Setup\EtypeController;
use App\Http\Controllers\HrPayroll\Setup\ShiftController;
use App\Http\Controllers\HrPayroll\Setup\DeptGroupController;
use App\Http\Controllers\HrPayroll\Setup\JobStatusController;
use App\Http\Controllers\HrPayroll\Setup\LeftStatusController;
use App\Http\Controllers\HrPayroll\Setup\RamazanController;
use App\Http\Controllers\HrPayroll\Setup\AllowdedController;
use App\Http\Controllers\HrPayroll\Setup\AllowedGroupController;
use App\Http\Controllers\HrPayroll\Setup\ReligionController;
use App\Http\Controllers\HrPayroll\Setup\LeaveBalanceController;
use App\Http\Controllers\HrPayroll\Setup\ProbationStatusController;
use App\Http\Controllers\HrPayroll\Setup\EntitlementTypeController;
use App\Http\Controllers\HrPayroll\Setup\EmployeeCodeListController;
use App\Http\Controllers\HrPayroll\Setup\SalaryTaxSlabController;
use App\Http\Controllers\HrPayroll\Setup\SaleTypesController;
use App\Http\Controllers\HrPayroll\Setup\LoanTypesController;
use App\Http\Controllers\HrPayroll\Setup\Reports\SetupReportsController;

use App\Http\Controllers\HrPayroll\Employee\EmployeeInfoController;
use App\Http\Controllers\HrPayroll\Employee\CardPrintingController;
use App\Http\Controllers\HrPayroll\Employee\FixTaxController;
use App\Http\Controllers\HrPayroll\Employee\SalAdvanceController;
use App\Http\Controllers\HrPayroll\Employee\LocalSaleController;
use App\Http\Controllers\HrPayroll\Employee\LoanEntryController;
use App\Http\Controllers\HrPayroll\Employee\LoanDeductionController;
use App\Http\Controllers\HrPayroll\Employee\Reports\EmployeeReportController;

use App\Http\Controllers\HrPayroll\TimeEntry\AttEntryController;
use App\Http\Controllers\HrPayroll\TimeEntry\ChangeAttendenceController;
use App\Http\Controllers\HrPayroll\TimeEntry\MonthDaysAttendanceController;
use App\Http\Controllers\HrPayroll\TimeEntry\OTEntryDailyController;
use App\Http\Controllers\HrPayroll\TimeEntry\OTEntryMonthlyController;
use App\Http\Controllers\HrPayroll\TimeEntry\OfficialVisitEntryController;
use App\Http\Controllers\HrPayroll\TimeEntry\LeaveApplyController;
use App\Http\Controllers\HrPayroll\TimeEntry\AttCorrectionController;

use App\Http\Controllers\HrPayroll\Posting\SalaryPostingController;
use App\Http\Controllers\HrPayroll\Posting\DailyPostingController;

use App\Http\Controllers\HrPayroll\Reports\DailyAttReportController;
use App\Http\Controllers\HrPayroll\Reports\MonthlyAttReportController;
use App\Http\Controllers\HrPayroll\Reports\MonthlySalaryReportController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/ 
//Route::get('/', function () { return view('home'); });

Route::get('/', [LoginController::class,'showLoginForm'])->name('login');
Route::get('login', [LoginController::class,'showLoginForm'])->name('login');
Route::post('login', [LoginController::class,'login'])->middleware('beforelogin');
Route::post('register', [RegisterController::class,'register']);
Route::get('password/forget',  function () { 
	return view('pages.forgot-password'); 
})->name('password.forget');

Route::post('password/email', [ForgotPasswordController::class,'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class,'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class,'reset'])->name('password.update');
Route::get('/doublelogin', [DoubleloginController::class,'doublelogin'])->name('double-login');
Route::group(['middleware' => 'auth'], function(){

	$helperPermission = new Permission();
	Route::get('/forgot-password', function () { return view('pages.forgot-password'); });
	Route::get('/register', function () { return view('pages.register'); });
	Route::get('/login-1', function () { return view('pages.login'); });
	Route::get('/user/change-password', [UserController::class,'changePassword'])->name('change-password');
	Route::post('/user/change-password', [UserController::class,'changePasswordSave'])->name('change-password-save');

	// logout route
	Route::get('/logout', [LoginController::class,'logout']);	
	Route::get('/clear-cache', [HomeController::class,'clearCache']);
Route::get('/permission', [PermissionController::class,'index']);
		Route::get('/permission/fillgrid', [PermissionController::class,'fillGrid']);
		Route::get('/permission/fillform/{id}', [PermissionController::class,'fillForm']);
		Route::post('/permission/create', [PermissionController::class,'create']);
		Route::get('/permission/update', [PermissionController::class,'update']);
		Route::get('/permission/delete/{id}', [PermissionController::class,'delete']);
		Route::get('get-role-permissions-badge', [PermissionController::class,'getPermissionBadgeByRole']);
	// Dashboard route   
	Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');
	Route::post('/dashboard/applyDateFilter', [DashboardController::class,'applyDateFilter'])->name('applyDateFilter');

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_USER], function(){
		Route::get('/users', [UserController::class,'index'])->name('users-index');
		Route::get('/user/get-list', [UserController::class,'getUserList']);
		Route::post('/user/create', [UserController::class,'save'])->name('create-user');
		Route::get('/user/getEmpData/{id}', [UserController::class,'getEmpData']);
		Route::get('/user/fillform/{id}', [UserController::class,'fillForm']);
		Route::post('/user/update', [UserController::class,'update']);
		Route::get('/user/delete/{id}', [UserController::class,'delete']);
		Route::get('/user/{id}', [UserController::class,'edit']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ROLE], function(){
		Route::get('/roles', [RolesController::class,'index']);
		Route::get('/role/fillgrid', [RolesController::class,'fillGrid']);
		Route::post('/role/save', [RolesController::class,'save']);
		Route::get('/role/edit/{id}', [RolesController::class,'edit']);
		Route::post('/role/update', [RolesController::class,'update']);
		Route::get('/role/delete/{id}', [RolesController::class,'delete']);
	});


	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_CONFIGS], function(){
		Route::get('/configurations', [ConfigurationsController::class,'index'])->name('configurations');
		Route::get('/configurations/fillgrid', [ConfigurationsController::class,'fillGrid'])->name('configurations-fillgrid');
		Route::get('/configurations/fillform/{id}', [ConfigurationsController::class,'fillForm'])->name('configurations-fillform');
		Route::post('/configurations/save', [ConfigurationsController::class,'save'])->name('configurations-save');
		Route::get('/configurations/delete/{id}', [ConfigurationsController::class,'delete'])->name('configurations-delete');
	 });

	//START SETUP ROUTES

	//Setup User Company Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_USER_COMPANY], function(){
		Route::get('/user/company/usercompany', [UserCompanyController::class,'index'])->name('user-company');
		Route::get('/user/company/fillgrid', [UserCompanyController::class,'fillGrid'])->name('user-company-fillgrid');
		Route::post('/user/company/save', [UserCompanyController::class,'save'])->name('user-company-save');
		Route::get('/user/company/delete/{id}', [UserCompanyController::class,'delete'])->name('user-company-delete');
		Route::get('/user/company/fillform/{id}', [UserCompanyController::class,'fillForm'])->name('user-company-fillform');
	});

	//Setup User Location Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_USER_LOCATION], function(){
		Route::get('/user/location/userlocation', [UserLocationController::class,'index'])->name('user-location');
		Route::get('/user/location/fillgrid', [UserLocationController::class,'fillGrid'])->name('user-location-fillgrid');
		Route::post('/user/location/save', [UserLocationController::class,'save'])->name('user-location-save');
		Route::get('/user/location/delete/{id}', [UserLocationController::class,'delete'])->name('user-location-delete');
		Route::get('/user/location/fillform/{id}', [UserLocationController::class,'fillForm'])->name('user-location-fillform');
	});

	// User Type Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_USER_TYPE], function(){
		Route::get('/user/type/usertype', [UserTypeController::class,'index'])->name('user-type');
		Route::get('/user/type/fillgrid', [UserTypeController::class,'fillGrid'])->name('user-type-fillgrid');
		Route::post('/user/type/save', [UserTypeController::class,'save'])->name('user-type-save');
		Route::get('/user/type/delete/{id}', [UserTypeController::class,'delete'])->name('user-type-delete');
		Route::get('/user/type/fillform/{id}', [UserTypeController::class,'fillForm'])->name('user-type-fillform');
	});



	// Setup Bank Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_BANK], function(){
		Route::get('/setup/bank', [SalaryBanksController::class,'index'])->name('setup-bank');
		Route::get('/setup/bank/fillgrid', [SalaryBanksController::class,'fillGrid'])->name('setup-bank-fillgrid');
		Route::get('/setup/bank/fillform/{id}', [SalaryBanksController::class,'fillForm'])->name('setup-bank-fillform');
		Route::post('/setup/bank/save', [SalaryBanksController::class,'save'])->name('setup-bank-save');
		Route::get('/setup/bank/delete/{id}', [SalaryBanksController::class,'delete'])
		->name('setup-bank-delete');
	});
	
	// Setup tenet Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_TENANT], function(){
		Route::get('/setup/tenant', [TenantController::class,'index'])->name('setup-tenant');
		Route::get('/setup/tenant/fillgrid', [TenantController::class,'fillGrid'])->name('setup-tenant-fillgrid');
		Route::get('/setup/tenant/fillform/{id}', [TenantController::class,'fillForm'])->name('setup-tenant-fillform');
		Route::post('/setup/tenant/save', [TenantController::class,'save'])->name('setup-tenant-save');
		Route::get('/setup/tenant/delete/{id}', [TenantController::class,'delete'])
		->name('setup-tenant-delete');	
	});

	//Setup Company Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_COMPANY], function(){
		Route::get('/setup/company', [CompanyController::class,'index'])->name('setup-company');
		Route::get('/setup/company/fillgrid', [CompanyController::class,'fillGrid'])->name('setup-company-fillgrid');
		Route::post('/setup/company/save', [CompanyController::class,'save'])->name('setup-company-save');
		Route::get('/setup/company/delete/{id}', [CompanyController::class,'delete'])->name('setup-company-delete');
		Route::get('/setup/company/fillform/{id}', [CompanyController::class,'fillForm'])->name('setup-company-fillform');
		Route::get('/setup/company/search', [CompanyController::class,'search'])->name('setup-company-search');
	});
	
	//Setup Country Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_COUNTRY], function(){
		Route::get('/setup/country', [CountryController::class,'index'])->name('setup-country');
		Route::get('/setup/country/fillgrid', [CountryController::class,'fillGrid'])->name('setup-country-fillgrid');
		Route::post('/setup/country/save', [CountryController::class,'save'])->name('setup-country-save');
		Route::get('/setup/country/delete/{id}', [CountryController::class,'delete'])->name('setup-country-delete');
		Route::get('/setup/country/fillform/{id}', [CountryController::class,'fillForm'])->name('setup-country-fillform');
	});

	// Setup Location Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_LOCATION], function(){
		Route::get('/setup/location', [LocationController::class,'index'])->name('setup-location');
		Route::get('/setup/location/fillgrid', [LocationController::class,'fillGrid'])->name('setup-location-fillgrid');
		Route::get('/setup/location/fillform/{id}', [LocationController::class,'fillForm'])->name('setup-location-fillform');
		Route::post('/setup/location/save', [LocationController::class,'save'])->name('setup-location-save');
		Route::get('/setup/location/delete/{id}', [LocationController::class,'delete'])
		->name('setup-location-delete');
	});
	
	//Setup Currency Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_CURRENCY], function(){
		Route::get('/setup/currency', [CurrencyController::class,'index'])->name('setup-currency');
		Route::get('/setup/currency/fillgrid', [CurrencyController::class,'fillGrid'])->name('setup-currency-fillgrid');
		Route::post('/setup/currency/save', [CurrencyController::class,'save'])->name('setup-currency-save');
		Route::get('/setup/currency/delete/{id}', [CurrencyController::class,'delete'])->name('setup-currency-delete');
		Route::get('/setup/currency/fillform/{id}', [CurrencyController::class,'fillForm'])->name('setup-currency-fillform');
	});

	//Setup Designation Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_DESG], function(){
		Route::get('/setup/desg', [DesgController::class,'index'])->name('setup-desg');
		Route::get('/setup/desg/fillgrid', [DesgController::class,'fillGrid'])->name('setup-desg-fillgrid');
		Route::get('/setup/desg/fillform/{id}', [DesgController::class,'fillForm'])->name('setup-desg-fillform');
		Route::post('/setup/desg/save', [DesgController::class,'save'])->name('setup-desg-save');
		Route::get('/setup/desg/delete/{id}', [DesgController::class,'delete'])->name('setup-desg-delete');
	});

	//Setup Grade Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_GRADE], function(){
		Route::get('/setup/grade', [GradeController::class,'index'])->name('setup-grade');
		Route::get('/setup/grade/fillgrid', [GradeController::class,'fillGrid'])->name('setup-grade-fillgrid');
		Route::get('/setup/grade/fillform/{id}', [GradeController::class,'fillForm'])->name('setup-grade-fillform');
		Route::post('/setup/grade/save', [GradeController::class,'save'])->name('setup-grade-save');
		Route::get('/setup/grade/delete/{id}', [GradeController::class,'delete'])->name('setup-grade-delete');
	});

	//Setup Departments Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_DEPT], function(){
		Route::get('/setup/dept', [DeptsController::class,'index'])->name('setup-dept');
		Route::get('/setup/dept/fillgrid', [DeptsController::class,'fillGrid'])->name('setup-dept-fillgrid');
		Route::get('/setup/dept/fillform/{id}', [DeptsController::class,'fillForm'])->name('setup-dept-fillform');
		Route::post('/setup/dept/save', [DeptsController::class,'save'])->name('setup-dept-save');
		Route::get('/setup/dept/delete/{id}', [DeptsController::class,'delete'])->name('setup-dept-delete');
	});

	//Setup Gender Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_GENDER], function(){
		Route::get('/setup/gender', [GenderController::class,'index'])->name('setup-gender');
		Route::get('/setup/gender/fillgrid', [GenderController::class,'fillGrid'])->name('setup-gender-fillgrid');
		Route::get('/setup/gender/fillform/{id}', [GenderController::class,'fillForm'])->name('setup-gender-fillform');
		Route::post('/setup/gender/save', [GenderController::class,'save'])->name('setup-gender-save');
		Route::get('/setup/gender/delete/{id}', [GenderController::class,'delete'])->name('setup-gender-delete');
	});	

	//Setup Holiday Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_HOLIDAY], function(){
		Route::get('/setup/holiday', [HolidayController::class,'index'])->name('setup-holiday');
		Route::get('/setup/holiday/fillgrid', [HolidayController::class,'fillGrid'])->name('setup-holiday-fillgrid');
		Route::get('/setup/holiday/fillform/{id}', [HolidayController::class,'fillForm'])->name('setup-holiday-fillform');
		Route::post('/setup/holiday/save', [HolidayController::class,'save'])->name('setup-holiday-save');
		Route::get('/setup/holiday/delete/{id}', [HolidayController::class,'delete'])->name('setup-holiday-delete');
	});

	//Setup Attendance Code Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_ATTCODE], function(){
		Route::get('/setup/attcode', [AttCodeController::class,'index'])->name('setup-attcode');
		Route::get('/setup/attcode/fillgrid', [AttCodeController::class,'fillGrid'])->name('setup-attcode-fillgrid');
		Route::get('/setup/attcode/fillform/{id}', [AttCodeController::class,'fillForm'])->name('setup-attcode-fillform');
		Route::post('/setup/attcode/save', [AttCodeController::class,'save'])->name('setup-attcode-save');
		Route::get('/setup/attcode/delete/{id}', [AttCodeController::class,'delete'])->name('setup-attcode-delete');
	});

	//Setup Weekday Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_WEEKDAY], function(){
		Route::get('/setup/weekday', [WeekdayController::class,'index'])->name('setup-weekday');
		Route::get('/setup/weekday/fillgrid', [WeekdayController::class,'fillGrid'])->name('setup-weekday-fillgrid');
		Route::get('/setup/weekday/fillform/{id}', [WeekdayController::class,'fillForm'])->name('setup-weekday-fillform');
		Route::post('/setup/weekday/save', [WeekdayController::class,'save'])->name('setup-weekday-save');
		Route::get('/setup/weekday/delete/{id}', [WeekdayController::class,'delete'])->name('setup-weekday-delete');
	});

	//Setup Attendance ETYPE Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_ETYPE], function(){
		Route::get('/setup/etype', [EtypeController::class,'index'])->name('setup-etype');
		Route::get('/setup/etype/fillgrid', [EtypeController::class,'fillGrid'])->name('setup-etype-fillgrid');
		Route::get('/setup/etype/fillform/{id}', [EtypeController::class,'fillForm'])->name('setup-etype-fillform');
		Route::post('/setup/etype/save', [EtypeController::class,'save'])->name('setup-etype-save');
		Route::get('/setup/etype/delete/{id}', [EtypeController::class,'delete'])->name('setup-etype-delete');
	});

	//Setup Shift Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_SHIFT], function(){
		Route::get('/setup/shift', [ShiftController::class,'index'])->name('setup-shift');
		Route::get('/setup/shift/fillgrid', [ShiftController::class,'fillGrid'])->name('setup-shift-fillgrid');
		Route::get('/setup/shift/fillform/{id}', [ShiftController::class,'fillForm'])->name('setup-shift-fillform');
		Route::post('/setup/shift/save', [ShiftController::class,'save'])->name('setup-shift-save');
		Route::get('/setup/shift/delete/{id}', [ShiftController::class,'delete'])->name('setup-shift-delete');
	});


	//Setup Department Group Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_DEPT_GROUP], function(){
		Route::get('/setup/dept-group', [DeptGroupController::class,'index'])->name('setup-dept-group');
		Route::get('/setup/dept-group/fillgrid', [DeptGroupController::class,'fillGrid'])->name('setup-dept-group-fillgrid');
		Route::get('/setup/dept-group/fillform/{id}', [DeptGroupController::class,'fillForm'])->name('setup-dept-group-fillform');
		Route::post('/setup/dept-group/save', [DeptGroupController::class,'save'])->name('setup-dept-group-save');
		Route::get('/setup/dept-group/delete/{id}', [DeptGroupController::class,'delete'])->name('setup-dept-group-delete');
	});

	//Setup Job Status Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_JOB_STATUS], function(){
		Route::get('/setup/job-status', [JobStatusController::class,'index'])->name('setup-job-status');
		Route::get('/setup/job-status/fillgrid', [JobStatusController::class,'fillGrid'])->name('setup-job-status-fillgrid');
		Route::get('/setup/job-status/fillform/{id}', [JobStatusController::class,'fillForm'])->name('setup-job-status-fillform');
		Route::post('/setup/job-status/save', [JobStatusController::class,'save'])->name('setup-job-status-save');
		Route::get('/setup/job-status/delete/{id}', [JobStatusController::class,'delete'])->name('setup-job-status-delete');
	});

	//Setup Left Status Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_LEFT_STATUS], function(){
		Route::get('/setup/left-status', [LeftStatusController::class,'index'])->name('setup-left-status');
		Route::get('/setup/left-status/fillgrid', [LeftStatusController::class,'fillGrid'])->name('setup-left-status-fillgrid');
		Route::get('/setup/left-status/fillform/{id}', [LeftStatusController::class,'fillForm'])->name('setup-left-status-fillform');
		Route::post('/setup/left-status/save', [LeftStatusController::class,'save'])->name('setup-left-status-save');
		Route::get('/setup/left-status/delete/{id}', [LeftStatusController::class,'delete'])->name('setup-left-status-delete');
	});

	//Setup Ramazan Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_RAMAZAN], function(){
		Route::get('/setup/ramazan', [RamazanController::class,'index'])->name('setup-ramazan');
		Route::get('/setup/ramazan/fillgrid', [RamazanController::class,'fillGrid'])->name('setup-ramazan-fillgrid');
		Route::get('/setup/ramazan/fillform/{id}', [RamazanController::class,'fillForm'])->name('setup-ramazan-fillform');
		Route::post('/setup/ramazan/save', [RamazanController::class,'save'])->name('setup-ramazan-save');
		Route::get('/setup/ramazan/delete/{id}', [RamazanController::class,'delete'])->name('setup-ramazan-delete');
	});

	//Setup Attendance Allowded Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_ALLOWDED], function(){
		Route::get('/setup/allowded', [AllowdedController::class,'index'])->name('setup-allowded');
		Route::get('/setup/allowded/fillgrid', [AllowdedController::class,'fillGrid'])->name('setup-allowded-fillgrid');
		Route::get('/setup/allowded/fillform/{id}', [AllowdedController::class,'fillForm'])->name('setup-allowded-fillform');
		Route::post('/setup/allowded/save', [AllowdedController::class,'save'])->name('setup-allowded-save');
		Route::get('/setup/allowded/delete/{id}', [AllowdedController::class,'delete'])->name('setup-allowded-delete');
	});

	//Setup Attendance Allowded Group Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_ALLOWDED_GROUP], function(){
		Route::get('/setup/allowed-group', [AllowedGroupController::class,'index'])->name('setup-alloweded-group');
		Route::get('/setup/allowed-group/fillgrid', [AllowedGroupController::class,'fillGrid'])->name('setup-alloweded-group-fillgrid');
		Route::get('/setup/allowed-group/fillform/{id}', [AllowedGroupController::class,'fillForm'])->name('setup-alloweded-group-fillform');
		Route::post('/setup/allowed-group/save', [AllowedGroupController::class,'save'])->name('setup-alloweded-group-save');
		Route::get('/setup/allowed-group/delete/{id}', [AllowedGroupController::class,'delete'])->name('setup-alloweded-group-delete');
	});
	//Setup Religion Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_RELIGION], function(){
		Route::get('/setup/religion', [ReligionController::class,'index'])->name('setup-religion');
		Route::get('/setup/religion/fillgrid', [ReligionController::class,'fillGrid'])->name('setup-religion-fillgrid');
		Route::get('/setup/religion/fillform/{id}', [ReligionController::class,'fillForm'])->name('setup-religion-fillform');
		Route::post('/setup/religion/save', [ReligionController::class,'save'])->name('setup-religion-save');
		Route::get('/setup/religion/delete/{id}', [ReligionController::class,'delete'])->name('setup-religion-delete');
	});

	//Setup Leave Balance Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_LEAVE], function(){
		Route::get('/setup/leave-balance', [LeaveBalanceController::class,'index'])->name('setup-leave-balance');
		Route::get('/setup/leave-balance/fillgrid', [LeaveBalanceController::class,'fillGrid'])->name('setup-leave-balance-fillgrid');
		Route::get('/setup/leave-balance/fillform/{id}', [LeaveBalanceController::class,'fillForm'])->name('setup-leave-balance-fillform');
		Route::get('/setup/leave-balance/getDate/', [LeaveBalanceController::class,'getFinancialYearDate'])->name('setup-leave-balance-getDate');
		Route::post('/setup/leave-balance/save', [LeaveBalanceController::class,'save'])->name('setup-leave-balance-save');
		Route::get('/setup/leave-balance/delete/{id}', [LeaveBalanceController::class,'delete'])->name('setup-leave-balance-delete');
	});

	//Setup Probation Status Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_PROBATION_STATUS], function(){
		Route::get('/setup/probation-status', [ProbationStatusController::class,'index'])->name('setup-probation-status');
		Route::get('/setup/probation-status/fillgrid', [ProbationStatusController::class,'fillGrid'])->name('setup-probation-status-fillgrid');
		Route::get('/setup/probation-status/fillform/{id}', [ProbationStatusController::class,'fillForm'])->name('setup-probation-status-fillform');
		Route::post('/setup/probation-status/save', [ProbationStatusController::class,'save'])->name('setup-probation-status-save');
		Route::get('/setup/probation-status/delete/{id}', [ProbationStatusController::class,'delete'])->name('setup-probation-status-delete');
	});

	//Setup Entitlement Type Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_ENTITLEMENT_TYPE], function(){
		Route::get('/setup/entitlement-type', [EntitlementTypeController::class,'index'])->name('setup-entitlement-type');
		Route::get('/setup/entitlement-type/fillgrid', [EntitlementTypeController::class,'fillGrid'])->name('setup-entitlement-type-fillgrid');
		Route::get('/setup/entitlement-type/fillform/{id}', [EntitlementTypeController::class,'fillForm'])->name('setup-entitlement-type-fillform');
		Route::post('/setup/entitlement-type/save', [EntitlementTypeController::class,'save'])->name('setup-entitlement-type-save');
		Route::get('/setup/entitlement-type/delete/{id}', [EntitlementTypeController::class,'delete'])->name('setup-entitlement-type-delete');
	});

	//Setup Employee Code List
    Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_EMP_CODE_LIST], function(){
		Route::get('/setup/employeecodelist', [EmployeeCodeListController::class,'index'])->name('employeecodelist');
		Route::get('/setup/employeecodelist/fillgrid', [EmployeeCodeListController::class,'fillGrid'])->name('employeecodelist-fillgrid');
		Route::get('/setup/employeecodelist/fillform/{id}', [EmployeeCodeListController::class,'fillForm'])->name('employeecodelist-fillform');
		Route::post('/setup/employeecodelist/save', [EmployeeCodeListController::class,'save'])->name('employeecodelist-save');
		Route::get('/setup/employeecodelist/delete/{id}', [EmployeeCodeListController::class,'delete'])->name('employeecodelist-delete');
	});

    //Setup SALARY TAX SLAB
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_SAL_TAX_SLAB], function(){
		Route::get('/setup/salary-tax-slab', [SalaryTaxSlabController::class,'index'])->name('salary-tax-slab');
		Route::get('/setup/salary-tax-slab/fillgrid', [SalaryTaxSlabController::class,'fillGrid'])->name('salary-tax-slab-fillgrid');
		Route::get('/setup/salary-tax-slab/fillform/{id}', [SalaryTaxSlabController::class,'fillForm'])->name('salary-tax-slab-fillform');
		Route::post('/setup/salary-tax-slab/save', [SalaryTaxSlabController::class,'save'])->name('salary-tax-slab-save');
		Route::get('/setup/salary-tax-slab/delete/{id}', [SalaryTaxSlabController::class,'delete'])->name('salary-tax-slab-delete');
	 });
	
	//Setup Sale Types
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_SALE_TYPES], function(){
		Route::get('/setup/sale-types', [SaleTypesController::class,'index'])->name('sale-types');
		Route::get('/setup/sale-types/fillgrid', [SaleTypesController::class,'fillGrid'])->name('sale-types-fillgrid');
		Route::get('/setup/sale-types/fillform/{id}', [SaleTypesController::class,'fillForm'])->name('sale-types-fillform');
		Route::post('/setup/sale-types/save', [SaleTypesController::class,'save'])->name('sale-types-save');
		Route::get('/setup/sale-types/delete/{id}', [SaleTypesController::class,'delete'])->name('sale-types-delete');
	});
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_LOAN_TYPES], function(){
		Route::get('/setup/loan-types', [LoanTypesController::class,'index'])->name('loan-types');
		Route::get('/setup/loan-types/fillgrid', [LoanTypesController::class,'fillGrid'])->name('loan-types-fillgrid');
		Route::get('/setup/loan-types/fillform/{id}', [LoanTypesController::class,'fillForm'])->name('loan-types-fillform');
		Route::post('/setup/loan-types/save', [LoanTypesController::class,'save'])->name('loan-types-save');
		Route::get('/setup/loan-types/delete/{id}', [LoanTypesController::class,'delete'])->name('loan-types-delete');
	});
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_SETUP_REPORTS], function(){
		Route::get('/reports/setup-report', [SetupReportsController::class,'index'])->name('setup-report');
		Route::get('/reports/setup-report/fillgrid/{radioBtnID}', [SetupReportsController::class,'fillGrid'])->name('setup-report-fillgrid');
	});

	// Start Employee Routes

	//Employee Main Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_EMP_INFO], function(){
		Route::get('/employee/employeeinfo', [EmployeeInfoController::class,'index'])->name('employeeinfo');
		Route::get('/employee/employeeinfo/fillgrid', [EmployeeInfoController::class,'fillGrid'])->name('employeeinfo-fillgrid');
		Route::get('/employee/employeeinfo/fillform/{id}', [EmployeeInfoController::class,'fillForm'])->name('employeeinfo-fillform');
		Route::post('/employee/employeeinfo/save', [EmployeeInfoController::class,'save'])->name('employeeinfo-save');
		Route::get('/employee/employeeinfo/delete/{id}', [EmployeeInfoController::class,'delete'])->name('employeeinfo-delete');
		Route::get('/employee/employeeinfo/search', [EmployeeInfoController::class,'search'])
		->name('employee-info-search');
		Route::get('/employee/employeeinfo/getEmpCode', [EmployeeInfoController::class,'getEmployeeCode'])
		->name('employee-info-getEmpCode');
	});

	//Employee Card Printing Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_EMP_CARD_PRINTING], function(){
		Route::get('/employee/card-printing', [CardPrintingController::class,'index'])->name('employee-card-printing');
		Route::get('/employee/card-printing/fillgrid/{deptid?}/{etypeid}/{locationid}', [CardPrintingController::class,'fillGrid'])->name('employee-card-printing-fillgrid');
		Route::get('/employee/card-printing/fill', [CardPrintingController::class,'fill'])->name('employee-card-printing-fill');
		Route::get('/employee/card-printing/setEmpIdsInSession', [CardPrintingController::class,'setEmpIdsInSession'])->name('employee-card-printing-setEmpIdsInSession');
		Route::get('/employee/card-printing/print', [CardPrintingController::class,'print'])->name('employee-card-printing-print');
	});


	//Employee Fix Tax Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_EMP_FIX_TAX], function(){
		Route::get('/employee/fix-tax', [FixTaxController::class,'index'])->name('employee-fix-tax');
		Route::get('/employee/fix-tax/fillgrid', [FixTaxController::class,'fillGrid'])->name('employee-fix-tax-fillgrid');
		Route::get('/employee/fix-tax/fillform/{id}', [FixTaxController::class,'fillForm'])->name('employee-fix-tax-fillform');
		Route::post('/employee/fix-tax/save', [FixTaxController::class,'save'])->name('employee-fix-tax-save');
		Route::get('/employee/fix-tax/delete/{id}', [FixTaxController::class,'delete'])->name('employee-fix-tax-delete');
	});

	//Employee Advance Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_EMP_Advance], function(){
		Route::get('/employee/advance', [SalAdvanceController::class,'index'])->name('employee-advance');
		Route::get('/employee/advance/fillgrid', [SalAdvanceController::class,'fillGrid'])->name('employee-advance-fillgrid');
		Route::get('/employee/advance/fillform/{id}', [SalAdvanceController::class,'fillForm'])->name('employee-advance-fillform');
		Route::post('/employee/advance/save', [SalAdvanceController::class,'save'])->name('employee-advance-save');
		Route::get('/employee/advance/delete/{id}', [SalAdvanceController::class,'delete'])->name('employee-advance-delete');
	});

	//Employee Local Sale Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_EMP_LOCAL_SALE], function(){
		Route::get('/employee/local-sale', [LocalSaleController::class,'index'])->name('employee-local-sale');
		Route::get('/employee/local-sale/fillgrid', [LocalSaleController::class,'fillGrid'])->name('employee-local-sale-fillgrid');
		Route::get('/employee/local-sale/fillform/{id}', [LocalSaleController::class,'fillForm'])->name('employee-local-sale-fillform');
		Route::post('/employee/local-sale/save', [LocalSaleController::class,'save'])->name('employee-local-sale-save');
		Route::get('/employee/local-sale/delete/{id}', [LocalSaleController::class,'delete'])->name('employee-local-sale-delete');
	});

	//Employee Loan Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_EMP_Loan], function(){
		Route::get('/employee/loan', [LoanEntryController::class,'index'])->name('employee-loan');
		Route::get('/employee/loan/fillgrid', [LoanEntryController::class,'fillGrid'])->name('employee-loan-fillgrid');
		Route::get('/employee/loan/fillform/{id}', [LoanEntryController::class,'fillForm'])->name('employee-loan-fillform');
		Route::post('/employee/loan/save', [LoanEntryController::class,'save'])->name('employee-loan-save');
		Route::get('/employee/loan/delete/{id}', [LoanEntryController::class,'delete'])->name('employee-loan-delete');
	});

	//Employee Loan Deduction Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_LOAN_DEDUCTION], function(){
		Route::get('/employee/loan-deduction', [LoanDeductionController::class,'index'])->name('employee-loan-deduction');
		Route::get('/employee/loan-deduction/fillgrid', [LoanDeductionController::class,'fillGrid'])->name('employee-loan-deduction-fillgrid');
		Route::get('/employee/loan-deduction/fillform/{id}', [LoanDeductionController::class,'fillForm'])->name('employee-loan-deduction-fillform');
		Route::post('/employee/loan-deduction/save', [LoanDeductionController::class,'save'])->name('employee-loan-deduction-save');
		Route::post('/employee/loan-deduction/search', [LoanDeductionController::class,'search'])->name('employee-loan-deduction-search');
		Route::get('/employee/loan-deduction/delete/{id}', [LoanDeductionController::class,'delete'])->name('employee-loan-deduction-delete');
	});

	//Employee Report Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_EMP_REPORT], function(){
		Route::get('/employee/report', [EmployeeReportController::class,'index'])->name('employee-report');
		Route::get('/employee/report/fillgrid', [EmployeeReportController::class,'fillGrid'])->name('employee-report-fillgrid');
	});

	
	//START TIME ENTRY ROUTES

	// Entry Routes 
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_TE_Att_ENTRY], function(){
		Route::get('/time-entry/attendance-entry', [AttEntryController::class,'index'])->name('time-entry-attentry');
		Route::get('/time-entry/attendance-entry/fillgrid', [AttEntryController::class,'fillGrid'])->name('attendance-entry-fillgrid');
		Route::get('/time-entry/attendance-entry/getEmployees', [AttEntryController::class,'getEmployees'])->name('attendance-entry-getEmployees');
		Route::get('/time-entry/attendance-entry/fillform/{id}', [AttEntryController::class,'fillForm'])->name('attendance-entry-fillform');
		Route::post('/time-entry/attendance-entry/save', [AttEntryController::class,'save'])->name('attendance-entry-save');
		Route::post('/time-entry/attendance-entry/search', [AttEntryController::class,'search'])->name('attendance-entry-search');
		Route::get('/time-entry/attendance-entry/delete/{id}', [AttEntryController::class,'delete'])->name('attendance-entry-delete');
	});

	//Change ATT Employee
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_TE_CHANGE_Att_EMPLOYEE], function(){
		Route::get('/time-entry/change-attendence', [ChangeAttendenceController::class,'index'])->name('att-change-attendence');
		Route::post('/time-entry/change-attendence/save', [ChangeAttendenceController::class,'save'])->name('att-change-attendence-save');
		Route::post('/time-entry/change-attendence/search', [ChangeAttendenceController::class,'search'])->name('att-change-attendence-search');
	});

	//Employee Month Attendance Days Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_EMP_MONTH_ATT_DAYS], function(){
		Route::get('/time-entry/month-days', [MonthDaysAttendanceController::class,'index'])->name('employee-month-days');
		Route::get('/time-entry/month-days/fillgrid', [MonthDaysAttendanceController::class,'fillGrid'])->name('employee-month-days-fillgrid');
		Route::get('/time-entry/month-days/fillform/{id}', [MonthDaysAttendanceController::class,'fillForm'])->name('employee-month-days-fillform');
		Route::post('/time-entry/month-days/save', [MonthDaysAttendanceController::class,'save'])->name('employee-month-days-save');
		Route::post('/time-entry/month-days/search', [MonthDaysAttendanceController::class,'search'])->name('employee-month-days-search');
	});

	//Daily Manual O/T
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_TE_DAILY_MANUAL_OT], function(){
		Route::get('/time-entry/daily-manual-ot', [OTEntryDailyController::class,'index'])->name('daily-manual-ot');
		Route::post('/time-entry/daily-manual-ot/save', [OTEntryDailyController::class,'save'])->name('daily-manual-ot-save');
		Route::post('/time-entry/daily-manual-ot/search', [OTEntryDailyController::class,'search'])->name('daily-manual-ot-search');
	});

	//Monthly O/T
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_TE_MONTHLY_OT_ENTRY], function(){
		Route::get('/time-entry/ot-entry-monthly', [OTEntryMonthlyController::class,'index'])->name('ot-entry-monthly');
		Route::get('/time-entry/ot-entry-monthly/fillgrid', [OTEntryMonthlyController::class,'fillGrid'])->name('ot-entry-monthly-fillgrid');
		Route::post('/time-entry/ot-entry-monthly/save', [OTEntryMonthlyController::class,'save'])->name('ot-entry-monthly-save');
		Route::post('/time-entry/ot-entry-monthly/search', [OTEntryMonthlyController::class,'search'])->name('ot-entry-monthly-search');
	});

	//Official Visit Entry
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_TE_OFFICIAL_VISIT_ENTRY], function(){
		Route::get('/time-entry/official-visit-entry', [OfficialVisitEntryController::class,'index'])->name('official-visit-entry');
		Route::get('/time-entry/official-visit-entry/fillgrid', [OfficialVisitEntryController::class,'fillGrid'])->name('official-visit-entry-fillgrid');
		Route::get('/time-entry/official-visit-entry/fillform/{id}', [OfficialVisitEntryController::class,'fillForm'])->name('official-visit-entry-fillform');
		Route::post('/time-entry/official-visit-entry/save', [OfficialVisitEntryController::class,'save'])->name('official-visit-entry-save');
		Route::get('/time-entry/official-visit-entry/delete/{id}', [OfficialVisitEntryController::class,'delete'])->name('official-visit-entry-delete');
	});

	//Leave ENTRY
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_TE_LEAVE_ENTRY], function(){
		Route::get('/time-entry/leave-entry', [LeaveApplyController::class,'index'])->name('leave-entry');
		Route::get('/time-entry/leave-entry/fillgrid', [LeaveApplyController::class,'fillGrid'])->name('leave-entry-fillgrid');
		Route::get('/time-entry/leave-entry/fillform/{id}', [LeaveApplyController::class,'fillForm'])->name('leave-entry-fillform');
		Route::post('/time-entry/leave-entry/save', [LeaveApplyController::class,'save'])->name('leave-entry-save');
		Route::get('/time-entry/leave-entry/delete/{id}', [LeaveApplyController::class,'delete'])->name('leave-entry-delete');
	});

	//Leave ENTRY
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_TE_LEAVE_ENTRY], function(){
		Route::get('/time-entry/leave-approval', [LeaveApplyController::class,'indexLeaveApproval'])->name('leave-approval');
		Route::get('/time-entry/leave-approval/fillGridLeaveApproval', [LeaveApplyController::class,'fillGridLeaveApproval'])->name('leave-approval-fillgrid');
		// Route::get('/time-entry/leave-entry/fillform/{id}', [LeaveApplyController::class,'fillForm'])->name('leave-entry-fillform');
		Route::post('/time-entry/leave-approval/approve', [LeaveApplyController::class,'approve'])->name('leave-entry-save');
		// Route::get('/time-entry/leave-entry/delete/{id}', [LeaveApplyController::class,'delete'])->name('leave-entry-delete');
	});

	// End Time Entry Routes


	// Start Posting Routes
	// Posting Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_Att_POSTING], function(){
		Route::get('/posting/daily-posting', [DailyPostingController::class,'index'])->name('daily-posting');
		Route::get('/posting/daily-posting/fillgrid/{deptid}/{etypeid}', [DailyPostingController::class,'fillGrid'])->name('daily-posting-fillgrid');
		Route::post('/posting/daily-posting/save', [DailyPostingController::class,'save'])->name('daily-posting-save');
	});

	//Salary Posting Routes
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_SALARY_POSTING], function(){
		Route::get('/posting/salary-posting', [SalaryPostingController::class,'index'])->name('salary-posting');
		Route::get('/posting/salary-posting/fillgrid', [SalaryPostingController::class,'fillGrid'])->name('salary-posting-fillgrid');
		Route::get('/posting/salary-posting/fillform/{id}', [SalaryPostingController::class,'fillForm'])->name('salary-posting-fillform');
		Route::post('/posting/salary-posting/save', [SalaryPostingController::class,'save'])->name('salary-posting-save');
		Route::get('/posting/salary-posting/delete/{id}', [SalaryPostingController::class,'delete'])->name('salary-posting-delete');
	});
	//Start Reports Routes

	//Daily Attendance Report
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_Att_LISTING_REPORT], function(){
		Route::get('/reports/attendance-listing-report', [DailyAttReportController::class,'index'])->name('attendance-listing-report');
		Route::get('/reports/attendance-listing-report/fillgrid', [DailyAttReportController::class,'fillGrid'])->name('attendance-listing-report-fillgrid');
	});
	
	//Monthly Attendance Report
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_Att_MONTHLY_REPORT], function(){
		Route::get('/reports/monthy-attendence-report', [MonthlyAttReportController::class,'index'])->name('monthy-attendence-report');
		Route::get('/reports/monthy-attendence-report/fillgrid', [MonthlyAttReportController::class,'fillGrid'])->name('monthy-attendence-report-fillgrid');

		Route::get('/reports/monthy-attendance-report/setsession', [MonthlyAttReportController::class,'setsession'])->name('monthy-attendance-report-setsession');

		Route::get('/reports/monthy-attendance-report/atendancecard', [MonthlyAttReportController::class,'atendanceCard'])->name('monthy-attendance-report-atendancecard');

	});

	//Monthly Salary Report
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_MONTHLY_SALARY_REPORT], function(){
		Route::get('/reports/monthy-salary-report', [MonthlySalaryReportController::class,'index'])->name('monthy-salary-report');
		Route::get('/reports/monthy-salary-report/setsession', [MonthlySalaryReportController::class,'setsession'])->name('monthy-salary-report-setsession');
		Route::get('/reports/monthy-salary-report/salaryslip', [MonthlySalaryReportController::class,'salarySlip'])->name('monthy-salary-slip');
		Route::get('/reports/monthy-salary-report/salarysheet', [MonthlySalaryReportController::class,'salarySheet'])->name('monthy-salary-sheet');
		Route::post('/reports/monthy-salary-report/fillgrid', [MonthlySalaryReportController::class,'fillGrid'])->name('monthy-salary-report-fillgrid');

		
	});
});
