<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Helper\Permission;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RolesController;
use App\Http\Controllers\Api\PermissionController;

use App\Http\Controllers\Api\HrPayroll\Dashboard\DashboardController;
use App\Http\Controllers\Api\HrPayroll\Dashboard\MobileDashboardController;

use App\Http\Controllers\Api\User\UserController;
use App\Http\Controllers\Api\User\UserTypeController;
use App\Http\Controllers\Api\User\UserCompanyController;
use App\Http\Controllers\Api\User\UserLocationController;

use App\Http\Controllers\Api\HrPayroll\Setup\BankController;
use App\Http\Controllers\Api\HrPayroll\Setup\CompanyController;
use App\Http\Controllers\Api\HrPayroll\Setup\CountryController;
use App\Http\Controllers\Api\HrPayroll\Setup\ProjectController;
use App\Http\Controllers\Api\HrPayroll\Setup\LocationController;
use App\Http\Controllers\Api\HrPayroll\Setup\TenantController;
use App\Http\Controllers\Api\HrPayroll\Setup\FixTaxSlabController;
use App\Http\Controllers\Api\HrPayroll\Setup\LoanTypesController;
use App\Http\Controllers\Api\HrPayroll\Setup\SaleTypesController;
use App\Http\Controllers\Api\HrPayroll\Setup\Currency\CurrencyController;
use App\Http\Controllers\Api\HrPayroll\Setup\Currency\CurrencyExchangeController;
use App\Http\Controllers\Api\HrPayroll\Setup\FinancialYearController;
use App\Http\Controllers\Api\HrPayroll\Setup\DeptController;
use App\Http\Controllers\Api\HrPayroll\Setup\DesgController;
use App\Http\Controllers\Api\HrPayroll\Setup\ETypeController;
use App\Http\Controllers\Api\HrPayroll\Setup\ShiftController;
use App\Http\Controllers\Api\HrPayroll\Setup\GradeController;
use App\Http\Controllers\Api\HrPayroll\Setup\GenderController;
use App\Http\Controllers\Api\HrPayroll\Setup\AttCodeController;
use App\Http\Controllers\Api\HrPayroll\Setup\RamazanController;
use App\Http\Controllers\Api\HrPayroll\Setup\HolidayController;
use App\Http\Controllers\Api\HrPayroll\Setup\WeekdayController;
use App\Http\Controllers\Api\HrPayroll\Setup\AllowdedController;
use App\Http\Controllers\Api\HrPayroll\Setup\AttGroupController;
use App\Http\Controllers\Api\HrPayroll\Setup\ReligionController;
use App\Http\Controllers\Api\HrPayroll\Setup\DeptGroupController;
use App\Http\Controllers\Api\HrPayroll\Setup\JobStatusController;
use App\Http\Controllers\Api\HrPayroll\Setup\AttGlobalController;
use App\Http\Controllers\Api\HrPayroll\Setup\LeaveTypeController;
use App\Http\Controllers\Api\HrPayroll\Setup\LeftStatusController;
use App\Http\Controllers\Api\HrPayroll\Setup\LeaveBalanceController;
use App\Http\Controllers\Api\HrPayroll\Setup\AllowdedGroupController;
use App\Http\Controllers\Api\HrPayroll\Setup\ProbationStatusController;
use App\Http\Controllers\Api\HrPayroll\Setup\EntitlementTypeController;
use App\Http\Controllers\Api\HrPayroll\Setup\AnnouncementController;
use App\Http\Controllers\Api\HrPayroll\Setup\AttRosterShiftController;
use App\Http\Controllers\Api\Attendence\Transaction\AttendanceController;
use App\Http\Controllers\Api\Attendence\Transaction\LeaveController;
use App\Http\Controllers\Api\Attendence\Transaction\ClosingMonthController;

use App\Http\Controllers\Api\HrPayroll\Reports\SalarySlipController;

//Employee Routes
use App\Http\Controllers\Api\HrPayroll\Employee\EmployeeInfoController;
use App\Http\Controllers\Api\HrPayroll\Employee\TrialEmployeeEntryController;
use App\Http\Controllers\Api\HrPayroll\Employee\EmployeeTransferController;
use App\Http\Controllers\Api\HrPayroll\Employee\FixTaxController;
use App\Http\Controllers\Api\HrPayroll\Employee\LocalSaleController;
use App\Http\Controllers\Api\HrPayroll\Employee\LoanEntryController; 
use App\Http\Controllers\Api\HrPayroll\Employee\SalAdvanceController;
use App\Http\Controllers\Api\HrPayroll\Employee\SalLoanDeductController;
use App\Http\Controllers\Api\HrPayroll\Employee\SalLoanController;
use App\Http\Controllers\Api\HrPayroll\Employee\CardPrintingController;
use App\Http\Controllers\Api\HrPayroll\Employee\Reports\EmployeeReportController;

//Time Entry
use App\Http\Controllers\Api\HrPayroll\TimeEntry\OfficialVisitEntryController;
use App\Http\Controllers\Api\HrPayroll\TimeEntry\LeaveApplyController;
use App\Http\Controllers\Api\HrPayroll\TimeEntry\AttEntryController;
use App\Http\Controllers\Api\HrPayroll\TimeEntry\ChangeAttendenceController;
use App\Http\Controllers\Api\HrPayroll\TimeEntry\OTEntryDailyController;
use App\Http\Controllers\Api\HrPayroll\TimeEntry\OTEntryMonthlyController;
use App\Http\Controllers\Api\HrPayroll\TimeEntry\MonthDaysAttendanceController;
use App\Http\Controllers\Api\HrPayroll\TimeEntry\RosterEntryController;

//Attendance Report
use App\Http\Controllers\Api\HrPayroll\Reports\DailyAttReportController;
use App\Http\Controllers\Api\HrPayroll\Reports\MonthlyAttReportController;
use App\Http\Controllers\Api\HrPayroll\Reports\MonthlySalaryReportController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class,'login']);

Route::group(['middleware' => 'auth:api'], function(){

	$helperPermission = new Permission();
	
	Route::get('logout', [AuthController::class,'logout']);

	Route::get('profile', [AuthController::class,'profile']);
	// Route::post('change-password', [AuthController::class,'changePassword']);
	Route::post('update-profile', [AuthController::class,'updateProfile']);

	// User Routes Start
	Route::post('change-password', [UserController::class,'changePassword']);

	Route::post('/dashboard/attendance-main', [DashboardController::class, 'attMain']);
	Route::post('/dashboard/attendance-activity', [DashboardController::class, 'attendanceActivity']);
	Route::post('/dashboard/attendance-activity-emp', [MobileDashboardController::class, 'attendanceActivityEmp']);
	Route::post('/dashboard/month-wise-strength', [DashboardController::class, 'monthWiseStrength']);
	Route::post('/dashboard/dept-wise-strength', [DashboardController::class, 'deptWiseStrength']);
	Route::post('/dashboard/dept-month-wise-strength', [DashboardController::class, 'deptMonthWiseStrength']);
	Route::post('/dashboard/emp-month-wise-strength', [DashboardController::class, 'empMonthWiseStrength']);
	Route::post('/dashboard/dept-wise-salary', [DashboardController::class, 'deptWiseSalary']);
	Route::post('/dashboard/month-wise-salary', [DashboardController::class, 'monthWiseSalary']);

	//Mobile Routes

	Route::post('/mobile/dashboard/attendance-activity', [MobileDashboardController::class, 'attendanceActivity']);
	Route::post('/mobile/dashboard/attendance-activity-emp', [MobileDashboardController::class, 'attendanceActivityEmp']);
	Route::post('/mobile/dashboard/leave-balance', [MobileDashboardController::class, 'leaveBalance']);
	Route::post('/mobile/dashboard/graph-data', [MobileDashboardController::class, 'graphData']);


	//only those have manage_user permission will get access
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_USER], function(){
		Route::post('/users', [UserController::class,'list']);
		Route::post('/users/create', [UserController::class,'store']);
		Route::get('/users/delete/{id}', [UserController::class,'delete']);
		Route::post('/users/update/{id}', [UserController::class,'update']);
		Route::post('/users/change-role/{id}', [UserController::class,'changeRole']);
		Route::post('/users/{id}', [UserController::class,'list']);
	});

	//only those have manage_role permission will get access
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ROLE], function(){
		Route::get('/roles', [RolesController::class,'list']);
		Route::post('/role/create', [RolesController::class,'store']);
		Route::get('/role/{id}', [RolesController::class,'show']);
		Route::delete('/role/delete/{id}', [RolesController::class,'delete']);
		Route::post('/role/change-permission/{id}', [RolesController::class,'changePermissions']);
	});


	//only those have manage_permission permission will get access
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_PERMISSION], function(){
		Route::get('/permissions', [PermissionController::class,'list']);
		Route::post('/permission/create', [PermissionController::class,'store']);
		Route::get('/permission/{id}', [PermissionController::class,'show']);
		Route::delete('/permission/delete/{id}', [PermissionController::class,'delete']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_USER_TYPE], function(){
		Route::post('/user/types/', [UserTypeController::class,'list']);
		Route::post('/user/types/create', [UserTypeController::class,'create']);
		Route::post('/user/types/update/{id}', [UserTypeController::class,'update']);
		Route::get('/user/types/delete/{id}', [UserTypeController::class,'delete']);
		Route::post('/user/types/{id}', [UserTypeController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_USER_COMPANY], function(){
		Route::post('/user/companies/', [UserCompanyController::class,'list']);
		Route::post('/user/companies/create', [UserCompanyController::class,'create']);
		Route::post('/user/companies/update/{id}', [UserCompanyController::class,'update']);
		Route::get('/user/companies/delete/{id}', [UserCompanyController::class,'delete']);
		Route::post('/user/companies/{id}', [UserCompanyController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_USER_LOCATION], function(){
		Route::post('/user/locations/', [UserLocationController::class,'list']);
		Route::post('/user/locations/create', [UserLocationController::class,'create']);
		Route::post('/user/locations/update/{id}', [UserLocationController::class,'update']);
		Route::get('/user/locations/delete/{id}', [UserLocationController::class,'delete']);
		Route::post('/user/locations/{id}', [UserLocationController::class,'list']);
	});

	// User Routes End

	// Account Routes Start
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_FINANCIAL_YEAR], function(){
		Route::post('/account/financial-years', [FinancialYearController::class,'list']);
		Route::post('/account/financial-years/create', [FinancialYearController::class,'create']);
		Route::post('/account/financial-years/update/{id}', [FinancialYearController::class,'update']);
		Route::get('/account/financial-years/delete/{id}', [FinancialYearController::class,'delete']);
		Route::post('/account/financial-years/{id}', [FinancialYearController::class,'list']);
	});

	// Account Routes End

	// Setup Routes Start

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_CURRENCY], function(){
		Route::post('/currencies', [CurrencyController::class,'list']);
		Route::post('/currencies/create', [CurrencyController::class,'create']);
		Route::post('/currencies/update/{id}', [CurrencyController::class,'update']);
		Route::get('/currencies/delete/{id}', [CurrencyController::class,'delete']);
		Route::post('/currencies/{id}', [CurrencyController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_COMPANY], function(){
		Route::post('/companies/', [CompanyController::class,'list']);
		Route::post('/companies/create', [CompanyController::class,'create']);
		Route::post('/companies/update/{id}', [CompanyController::class,'update']);
		Route::get('/companies/delete/{id}', [CompanyController::class,'delete']);
		Route::post('/companies/{id}', [CompanyController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_COUNTRY], function(){
		Route::post('/countries/', [CountryController::class,'list']);
		Route::post('/countries/create', [CountryController::class,'create']);
		Route::post('/countries/update/{id}', [CountryController::class,'update']);
		Route::get('/countries/delete/{id}', [CountryController::class,'delete']);
		Route::post('/countries/{id}', [CountryController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_PROJECT], function(){
		Route::post('/projects/', [ProjectController::class,'list']);
		Route::post('/projects/create', [ProjectController::class,'create']);
		Route::post('/projects/update/{id}', [ProjectController::class,'update']);
		Route::get('/projects/delete/{id}', [ProjectController::class,'delete']);
		Route::post('/projects/{id}', [ProjectController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_CURRENCY_EXCHANGE], function(){
		Route::post('/currency-exchanges/', [CurrencyExchangeController::class,'list']);
		Route::post('/currency-exchanges/create', [CurrencyExchangeController::class,'create']);
		Route::post('/currency-exchanges/update/{id}', [CurrencyExchangeController::class,'update']);
		Route::get('/currency-exchanges/delete/{id}', [CurrencyExchangeController::class,'delete']);
		Route::post('/currency-exchanges/{id}', [CurrencyExchangeController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_LOCATION], function(){
		Route::post('/locations/', [LocationController::class,'list']);
		Route::post('/locations/create', [LocationController::class,'create']);
		Route::post('/locations/update/{id}', [LocationController::class,'update']);
		Route::get('/locations/delete/{id}', [LocationController::class,'delete']);
		Route::post('/locations/{id}', [LocationController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_BANK], function(){
		Route::post('/banks/', [BankController::class,'list']);
		Route::post('/banks/create', [BankController::class,'create']);
		Route::post('/banks/update/{id}', [BankController::class,'update']);
		Route::get('/banks/delete/{id}', [BankController::class,'delete']);
		Route::post('/banks/{id}', [BankController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_TENANT], function(){
		Route::post('/tenants/', [TenantController::class,'list']);
		Route::post('/tenants/create', [TenantController::class,'create']);
		Route::post('/tenants/update/{id}', [TenantController::class,'update']);
		Route::get('/tenants/delete/{id}', [TenantController::class,'delete']);
		Route::post('/tenants/{id}', [TenantController::class,'list']);
	});

	// Setup Routes End

	// Attendence Route Start

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_DEPT_GROUP], function(){
		Route::post('/attendance/dept/groups/', [DeptGroupController::class,'list']);
		Route::post('/attendance/dept/groups/create', [DeptGroupController::class,'create']);
		Route::post('/attendance/dept/groups/update/{id}', [DeptGroupController::class,'update']);
		Route::get('/attendance/dept/groups/delete/{id}', [DeptGroupController::class,'delete']);
		Route::post('/attendance/dept/groups/{id}', [DeptGroupController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_ENTITLEMENT_TYPE], function(){
		Route::post('/attendance/entitlement/types/', [EntitlementTypeController::class,'list']);
		Route::post('/attendance/entitlement/types/create', [EntitlementTypeController::class,'create']);
		Route::post('/attendance/entitlement/types/update/{id}', [EntitlementTypeController::class,'update']);
		Route::get('/attendance/entitlement/types/delete/{id}', [EntitlementTypeController::class,'delete']);
		Route::post('/attendance/entitlement/types/{id}', [EntitlementTypeController::class,'list']);
	});	

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_EMP_Advance], function(){
		Route::post('/announcements', [AnnouncementController::class,'list']);
		Route::post('/announcement/create', [AnnouncementController::class,'create']);
		Route::post('/announcement/update/{id}', [AnnouncementController::class,'update']);
		Route::get('/announcement/delete/{id}', [AnnouncementController::class,'delete']);
		Route::post('/announcement/{id}', [AnnouncementController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_ROSTER_SHIFT], function(){
		Route::post('/attendance/roster-shift/', [AttRosterShiftController::class,'list']);
		Route::post('/attendance/roster-shift/create', [AttRosterShiftController::class,'create']);
		Route::post('/attendance/roster-shift/update/{id}', [AttRosterShiftController::class,'update']);
		Route::get('/attendance/roster-shift/delete/{id}', [AttRosterShiftController::class,'delete']);
		Route::post('/attendance/roster-shift/{id}', [AttRosterShiftController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_ETYPE], function(){
		Route::post('/attendance/etypes/', [ETypeController::class,'list']);
		Route::post('/attendance/etypes/create', [ETypeController::class,'create']);
		Route::post('/attendance/etypes/update/{id}', [ETypeController::class,'update']);
		Route::get('/attendance/etypes/delete/{id}', [ETypeController::class,'delete']);
		Route::post('/attendance/etypes/{id}', [ETypeController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_SAL_TAX_SLAB], function(){
		Route::post('/fixtaxslab/', [FixTaxSlabController::class,'list']);
		Route::post('/fixtaxslab/create', [FixTaxSlabController::class,'create']);
		Route::post('/fixtaxslab/update/{id}', [FixTaxSlabController::class,'update']);
		Route::get('/fixtaxslab/delete/{id}', [FixTaxSlabController::class,'delete']);
		Route::post('/fixtaxslab/{id}', [FixTaxSlabController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_SALE_TYPES], function(){
		Route::post('/setup/sale-types/', [SaleTypesController::class,'list']);
		Route::post('/setup/sale-types/create', [SaleTypesController::class,'create']);
		Route::post('/setup/sale-types/update/{id}', [SaleTypesController::class,'update']);
		Route::get('/setup/sale-types/delete/{id}', [SaleTypesController::class,'delete']);
		Route::post('/setup/sale-types/{id}', [SaleTypesController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_LOAN_TYPES], function(){
		Route::post('/setup/loan-types/', [LoanTypesController::class,'list']);
		Route::post('/setup/loan-types/create', [LoanTypesController::class,'create']);
		Route::post('/setup/loan-types/update/{id}', [LoanTypesController::class,'update']);
		Route::get('/setup/loan-types/delete/{id}', [LoanTypesController::class,'delete']);
		Route::post('/setup/loan-types/{id}', [LoanTypesController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_GENDER], function(){
		Route::post('/attendance/genders/', [GenderController::class,'list']);
		Route::post('/attendance/genders/create', [GenderController::class,'create']);
		Route::post('/attendance/genders/update/{id}', [GenderController::class,'update']);
		Route::get('/attendance/genders/delete/{id}', [GenderController::class,'delete']);
		Route::post('/attendance/genders/{id}', [GenderController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_GRADE], function(){
		Route::post('/attendance/grades/', [GradeController::class,'list']);
		Route::post('/attendance/grades/create', [GradeController::class,'create']);
		Route::post('/attendance/grades/update/{id}', [GradeController::class,'update']);
		Route::get('/attendance/grades/delete/{id}', [GradeController::class,'delete']);
		Route::post('/attendance/grades/{id}', [GradeController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_JOB_STATUS], function(){
		Route::post('/attendance/job-statuses/', [JobStatusController::class,'list']);
		Route::post('/attendance/job-statuses/create', [JobStatusController::class,'create']);
		Route::post('/attendance/job-statuses/update/{id}', [JobStatusController::class,'update']);
		Route::get('/attendance/job-statuses/delete/{id}', [JobStatusController::class,'delete']);
		Route::post('/attendance/job-statuses/{id}', [JobStatusController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_LEFT_STATUS], function(){
		Route::post('/attendance/left-statuses/', [LeftStatusController::class,'list']);
		Route::post('/attendance/left-statuses/create', [LeftStatusController::class,'create']);
		Route::post('/attendance/left-statuses/update/{id}', [LeftStatusController::class,'update']);
		Route::get('/attendance/left-statuses/delete/{id}', [LeftStatusController::class,'delete']);
		Route::post('/attendance/left-statuses/{id}', [LeftStatusController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_PROBATION_STATUS], function(){
		Route::post('/attendance/probation-statuses/', [ProbationStatusController::class,'list']);
		Route::post('/attendance/probation-statuses/create', [ProbationStatusController::class,'create']);
		Route::post('/attendance/probation-statuses/update/{id}', [ProbationStatusController::class,'update']);
		Route::get('/attendance/probation-statuses/delete/{id}', [ProbationStatusController::class,'delete']);
		Route::post('/attendance/probation-statuses/{id}', [ProbationStatusController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_RELIGION], function(){
		Route::post('/attendance/religions/', [ReligionController::class,'list']);
		Route::post('/attendance/religions/create', [ReligionController::class,'create']);
		Route::post('/attendance/religions/update/{id}', [ReligionController::class,'update']);
		Route::get('/attendance/religions/delete/{id}', [ReligionController::class,'delete']);
		Route::post('/attendance/religions/{id}', [ReligionController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_WEEKDAY], function(){
		Route::post('/attendance/weekdays/', [WeekdayController::class,'list']);
		Route::post('/attendance/weekdays/create', [WeekdayController::class,'create']);
		Route::post('/attendance/weekdays/update/{id}', [WeekdayController::class,'update']);
		Route::get('/attendance/weekdays/delete/{id}', [WeekdayController::class,'delete']);
		Route::post('/attendance/weekdays/{id}', [WeekdayController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_ATTCODE], function(){
		Route::post('/attendance/attcodes/', [AttCodeController::class,'list']);
		Route::post('/attendance/attcodes/create', [AttCodeController::class,'create']);
		Route::post('/attendance/attcodes/update/{id}', [AttCodeController::class,'update']);
		Route::get('/attendance/attcodes/delete/{id}', [AttCodeController::class,'delete']);
		Route::post('/attendance/attcodes/{id}', [AttCodeController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_RAMAZAN], function(){
		Route::post('/attendance/ramazans/', [RamazanController::class,'list']);
		Route::post('/attendance/ramazans/create', [RamazanController::class,'create']);
		Route::post('/attendance/ramazans/update/{id}', [RamazanController::class,'update']);
		Route::get('/attendance/ramazans/delete/{id}', [RamazanController::class,'delete']);
		Route::post('/attendance/ramazans/{id}', [RamazanController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_ALLOWDED_GROUP], function(){
		Route::post('/attendance/allowded-groups/', [AllowdedGroupController::class,'list']);
		Route::post('/attendance/allowded-groups/create', [AllowdedGroupController::class,'create']);
		Route::post('/attendance/allowded-groups/update/{id}', [AllowdedGroupController::class,'update']);
		Route::get('/attendance/allowded-groups/delete/{id}', [AllowdedGroupController::class,'delete']);
		Route::post('/attendance/allowded-groups/{id}', [AllowdedGroupController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_SHIFT], function(){
		Route::post('/attendance/shifts/', [ShiftController::class,'list']);
		Route::post('/attendance/shifts/create', [ShiftController::class,'create']);
		Route::post('/attendance/shifts/update/{id}', [ShiftController::class,'update']);
		Route::get('/attendance/shifts/delete/{id}', [ShiftController::class,'delete']);
		Route::post('/attendance/shifts/{id}', [ShiftController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_ATTGROUP], function(){
		Route::post('/attendance/attgroups/', [AttGroupController::class,'list']);
		Route::post('/attendance/attgroups/create', [AttGroupController::class,'create']);
		Route::post('/attendance/attgroups/update/{id}', [AttGroupController::class,'update']);
		Route::get('/attendance/attgroups/delete/{id}', [AttGroupController::class,'delete']);
		Route::post('/attendance/attgroups/{id}', [AttGroupController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_DEPT], function(){
		Route::post('/attendance/depts/', [DeptController::class,'list']);
		Route::post('/attendance/depts/create', [DeptController::class,'create']);
		Route::post('/attendance/depts/update/{id}', [DeptController::class,'update']);
		Route::get('/attendance/depts/delete/{id}', [DeptController::class,'delete']);
		Route::post('/attendance/depts/{id}', [DeptController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_DESG], function(){
		Route::post('/attendance/desgs/', [DesgController::class,'list']);
		Route::post('/attendance/desgs/create', [DesgController::class,'create']);
		Route::post('/attendance/desgs/update/{id}', [DesgController::class,'update']);
		Route::get('/attendance/desgs/delete/{id}', [DesgController::class,'delete']);
		Route::post('/attendance/desgs/{id}', [DesgController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_ALLOWDED], function(){
		Route::post('/attendance/allowded/', [AllowdedController::class,'list']);
		Route::post('/attendance/allowded/create', [AllowdedController::class,'create']);
		Route::post('/attendance/allowded/update/{id}', [AllowdedController::class,'update']);
		Route::get('/attendance/allowded/delete/{id}', [AllowdedController::class,'delete']);
		Route::post('/attendance/allowded/{id}', [AllowdedController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_LEAVE], function(){
		Route::post('/attendance/leave-balances/', [LeaveBalanceController::class,'list']);
		Route::post('/attendance/leave-balances/create', [LeaveBalanceController::class,'create']);
		Route::post('/attendance/leave-balances/update/{id}', [LeaveBalanceController::class,'update']);
		Route::get('/attendance/leave-balances/delete/{id}', [LeaveBalanceController::class,'delete']);
		Route::post('/attendance/leave-balances/{id}', [LeaveBalanceController::class,'list']);
	});

	// Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_LEAVE_TYPE], function(){
		Route::post('/attendance/leave-types/', [LeaveTypeController::class,'list']);
		Route::post('/attendance/leave-types/create', [LeaveTypeController::class,'create']);
		Route::post('/attendance/leave-types/update/{id}', [LeaveTypeController::class,'update']);
		Route::get('/attendance/leave-types/delete/{id}', [LeaveTypeController::class,'delete']);
		Route::post('/attendance/leave-types/{id}', [LeaveTypeController::class,'list']);
	// });

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_HOLIDAY], function(){
		Route::post('/attendance/holidays/', [HolidayController::class,'list']);
		Route::post('/attendance/holidays/create', [HolidayController::class,'create']);
		Route::post('/attendance/holidays/update/{id}', [HolidayController::class,'update']);
		Route::get('/attendance/holidays/delete/{id}', [HolidayController::class,'delete']);
		Route::post('/attendance/holidays/{id}', [HolidayController::class,'list']);
	});

	//Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_ATTENDANCE], function(){
		Route::post('/attendances/', [AttendanceController::class,'list']);
		Route::post('/attendances/create', [AttendanceController::class,'create']);
		Route::post('/attendances/update/{id}', [AttendanceController::class,'update']);
		Route::get('/attendances/delete/{id}', [AttendanceController::class,'delete']);
		Route::post('/attendances/{id}', [AttendanceController::class,'list']);
	//});

	//Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_LEAVE], function(){
		Route::post('/attendance/leaves/', [LeaveController::class,'list']);
		Route::post('/attendance/leaves/create', [LeaveController::class,'create']);
		Route::post('/attendance/leaves/update/{id}', [LeaveController::class,'update']);
		Route::get('/attendance/leaves/delete/{id}', [LeaveController::class,'delete']);
		Route::post('/attendance/leaves/{id}', [LeaveController::class,'list']);
	//});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_CLOSING_MONTH], function(){
		Route::post('/attendance/closing-month/save', [ClosingMonthController::class,'create']);
		
	});

	//Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_CLOSING_MONTH], function(){
		Route::get('/attendance/salaryslip/{id}', [SalarySlipController::class,'list'])->name('salaryslip');
		Route::get('/attendance/salary-slip/{employeeid}', [SalarySlipController::class,'salarySlip']);
		
	//});

		

	//Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_GLOBAL], function(){
		Route::post('/attendance/globals/', [AttGlobalController::class,'list']);
		Route::post('/attendance/globals/create', [AttGlobalController::class,'create']);
		Route::post('/attendance/globals/update/{id}', [AttGlobalController::class,'update']);
		Route::get('/attendance/globals/delete/{id}', [AttGlobalController::class,'delete']);
		Route::post('/attendance/globals/{id}', [AttGlobalController::class,'list']);
	//});
		
	// Attendence Route End

	//Employee Routes Start
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_EMP_INFO], function(){
		Route::post('/employee/employeeinfo', [EmployeeInfoController::class,'list']);
		Route::post('/employee/employeeinfo/create', [EmployeeInfoController::class,'create']);
		Route::post('/employee/employeeinfo/update/{id}', [EmployeeInfoController::class,'update']);
		Route::get('/employee/employeeinfo/delete/{id}', [EmployeeInfoController::class,'delete']);
		Route::post('/employee/employeeinfo/{id}', [EmployeeInfoController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_EMP_TRIAL], function(){
		Route::post('/employee/trial-employee-entry', [TrialEmployeeEntryController::class,'list']);
		Route::post('/employee/trial-employee-entry/create', [TrialEmployeeEntryController::class,'create']);
		Route::post('/employee/trial-employee-entry/update/{id}', [TrialEmployeeEntryController::class,'update']);
		Route::get('/employee/trial-employee-entry/delete/{id}', [TrialEmployeeEntryController::class,'delete']);
		Route::post('/employee/trial-employee-entry/{id}', [TrialEmployeeEntryController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_EMP_FIX_TAX], function(){
		Route::post('/employee/fix-tax', [FixTaxController::class,'list']);
		Route::post('/employee/fix-tax/create', [FixTaxController::class,'create']);
		Route::post('/employee/fix-tax/update/{id}', [FixTaxController::class,'update']);
		Route::get('/employee/fix-tax/delete/{id}', [FixTaxController::class,'delete']);
		Route::post('/employee/fix-tax/{id}', [FixTaxController::class,'list']);
	});	

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_EMP_LOC_TRANSFER], function(){
		Route::post('/employee/employeetransfer', [EmployeeTransferController::class,'list']);
		Route::post('/employee/employeetransfer/create', [EmployeeTransferController::class,'create']);
		Route::post('/employee/employeetransfer/update/{id}', [EmployeeTransferController::class,'update']);
		Route::get('/employee/employeetransfer/delete/{id}', [EmployeeTransferController::class,'delete']);
		Route::post('/employee/employeetransfer/{id}', [EmployeeTransferController::class,'list']);
	});	

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_EMP_LOCAL_SALE], function(){
		Route::post('/employee/local-sale', [LocalSaleController::class,'list']);
		Route::post('/employee/local-sale/create', [LocalSaleController::class,'create']);
		Route::post('/employee/local-sale/update/{id}', [LocalSaleController::class,'update']);
		Route::get('/employee/local-sale/delete/{id}', [LocalSaleController::class,'delete']);
		Route::post('/employee/local-sale/{id}', [LocalSaleController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_EMP_Loan], function(){
		Route::post('/employee/loan', [LoanEntryController::class,'list']);
		Route::post('/employee/loan/create', [LoanEntryController::class,'create']);
		Route::post('/employee/loan/update/{id}', [LoanEntryController::class,'update']);
		Route::get('/employee/loan/delete/{id}', [LoanEntryController::class,'delete']);
		Route::post('/employee/loan/{id}', [LoanEntryController::class,'list']);
	});	

	//Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_SAL_ADVANCE], function(){
		Route::post('/employee/sal-advance/', [SalAdvanceController::class,'list']);
		Route::post('/employee/sal-advance/create', [SalAdvanceController::class,'create']);
		Route::post('/employee/sal-advance/update/{id}', [SalAdvanceController::class,'update']);
		Route::get('/employee/sal-advance/delete/{id}', [SalAdvanceController::class,'delete']);
		Route::post('/employee/sal-advance/{id}', [SalAdvanceController::class,'list']);
	//});

	//Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_SAL_LOAN], function(){
		Route::post('/employee/sal-loan/', [SalLoanController::class,'list']);
		Route::post('/employee/sal-loan/create', [SalLoanController::class,'create']);
		Route::post('/employee/sal-loan/update/{id}', [SalLoanController::class,'update']);
		Route::get('/employee/sal-loan/delete/{id}', [SalLoanController::class,'delete']);
		Route::post('/employee/sal-loan/{id}', [SalLoanController::class,'list']);
	//});
	
	//Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_ATT_SAL_LOAN_DEDUCT], function(){
		Route::post('/employee/sal-loan-deduct/', [SalLoanDeductController::class,'list']);
		Route::post('/employee/sal-loan-deduct/create', [SalLoanDeductController::class,'create']);
		Route::post('/employee/sal-loan-deduct/update/{id}', [SalLoanDeductController::class,'update']);
		Route::get('/employee/sal-loan-deduct/delete/{id}', [SalLoanDeductController::class,'delete']);
		Route::post('/employee/sal-loan-deduct/{id}', [SalLoanDeductController::class,'list']);
	//});

		Route::post('/attendance/roster-entry/', [RosterEntryController::class,'list']);
		Route::post('/attendance/roster-entry/create', [RosterEntryController::class,'create']);
		Route::post('/attendance/roster-entry/update/{id}', [RosterEntryController::class,'update']);
		Route::get('/attendance/roster-entry/delete/{id}', [RosterEntryController::class,'delete']);
		Route::post('/attendance/roster-entry/{id}', [RosterEntryController::class,'list']);

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_EMP_CARD_PRINTING], function(){
		Route::post('employee/card-printing/fill', [CardPrintingController::class,'list']);
	});	
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_EMP_REPORT], function(){
		Route::post('employee/report/fill', [EmployeeReportController::class,'list']);
	});	
	//Employee Routes End

	//Time Entry Routes Start
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_TE_OFFICIAL_VISIT_ENTRY], function(){
		Route::post('/time-entry/official-visit-entry', [OfficialVisitEntryController::class,'list']);
		Route::post('/time-entry/official-visit-entry/create', [OfficialVisitEntryController::class,'create']);
		Route::post('/time-entry/official-visit-entry/update/{id}', [OfficialVisitEntryController::class,'update']);
		Route::get('/time-entry/official-visit-entry/delete/{id}', [OfficialVisitEntryController::class,'delete']);
		Route::post('/time-entry/official-visit-entry/{id}', [OfficialVisitEntryController::class,'list']);
	});
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_TE_LEAVE_ENTRY], function(){
		Route::post('/time-entry/leave-entry', [LeaveApplyController::class,'list']);
		Route::post('/time-entry/leave-entry/create', [LeaveApplyController::class,'create']);
		Route::post('/time-entry/leave-entry/update/{id}', [LeaveApplyController::class,'update']);
		Route::get('/time-entry/leave-entry/delete/{id}', [LeaveApplyController::class,'delete']);
		Route::post('/time-entry/leave-entry/{id}', [LeaveApplyController::class,'list']);
	});
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_TE_Att_ENTRY], function(){
		Route::post('/time-entry/att-entry', [AttEntryController::class,'list']);
		Route::post('/time-entry/att-entry/create', [AttEntryController::class,'create']);
		Route::post('/time-entry/att-entry/update/{id}', [AttEntryController::class,'update']);
		Route::get('/time-entry/att-entry/delete/{id}', [AttEntryController::class,'delete']);
		Route::post('/time-entry/att-entry/{id}', [AttEntryController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_TE_CHANGE_Att_EMPLOYEE], function(){
		Route::post('/time-entry/change-attendence', [ChangeAttendenceController::class,'list']);
		Route::post('/time-entry/change-attendence/create', [ChangeAttendenceController::class,'create']);
		Route::post('/time-entry/change-attendence/update/{id}', [ChangeAttendenceController::class,'update']);
		Route::post('/time-entry/change-attendence/{id}', [ChangeAttendenceController::class,'list']);
	});	

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_EMP_MONTH_ATT_DAYS], function(){
		Route::post('/time-entry/month-att-days/', [MonthDaysAttendanceController::class,'list']);
		Route::post('/time-entry/month-att-days/create', [MonthDaysAttendanceController::class,'create']);
		Route::post('/time-entry/month-att-days/update/{id}', [MonthDaysAttendanceController::class,'update']);
		Route::get('/time-entry/month-att-days/delete/{id}', [MonthDaysAttendanceController::class,'delete']);
		Route::post('/time-entry/month-att-days/{id}', [MonthDaysAttendanceController::class,'list']);
	});

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_TE_DAILY_MANUAL_OT], function(){
		Route::post('/attendance/daily-manual-ot', [OTEntryDailyController::class,'list']);
		Route::post('/attendance/daily-manual-ot/update/{id}', [OTEntryDailyController::class,'update']);
		Route::post('/attendance/daily-manual-ot/{id}', [OTEntryDailyController::class,'list']);
	});
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_TE_MONTHLY_OT_ENTRY], function(){
		Route::post('/attendance/ot-entry-monthly', [OTEntryMonthlyController::class,'list']);
		Route::post('/attendance/ot-entry-monthly/create', [OTEntryMonthlyController::class,'create']);
		Route::post('/attendance/ot-entry-monthly/update/{id}', [OTEntryMonthlyController::class,'update']);
		Route::get('/attendance/ot-entry-monthly/delete/{id}', [OTEntryMonthlyController::class,'delete']);
		Route::post('/attendance/ot-entry-monthly/{id}', [OTEntryMonthlyController::class,'list']);
	});
	
	//Attendance Reports

	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_Att_LISTING_REPORT], function(){
		Route::post('/reports/attendance-listing-report', [DailyAttReportController::class,'list']);
	});
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_Att_MONTHLY_REPORT], function(){
		Route::post('/reports/monthy-attendence-report', [MonthlyAttReportController::class,'list']);
	});
	Route::group(['middleware' => 'can:'.$helperPermission->MANAGE_MONTHLY_SALARY_REPORT], function(){
		Route::post('/reports/monthy-salary-report', [MonthlySalaryReportController::class,'list']);
	});

});