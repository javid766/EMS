<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use DataTables,Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\User;
use App\Models\Utils;
use App\Models\HrPayroll\Setup\Tenant;
use App\Models\HrPayroll\Setup\Company;
use App\Models\User\UserType;
use App\Models\User\UserCompany;
use App\Models\User\UserLocation;
use App\Models\HrPayroll\Setup\FinancialYear;
use App\Models\HrPayroll\Employee\EmployeeInfo;
use App\Helper\Permission as HelperPermission;

class UserController extends Controller {

    public $userModel;
    public $utilsModel;
    public $companyModel;
    public $userTypeModel;
    public $setupTenantModel;
    public $userCompanyModel;
    public $userLocationModel;
    public $FinancialYearModel;
    public $employeeModel;

    public $helperPermission;

    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct() {

        $this->middleware('auth');
        $this->employeeInfoModel = new EmployeeInfo();    
        $this->userModel             = new User();
        $this->utilsModel            = new Utils();
        $this->setupTenantModel      = new Tenant();
        $this->companyModel          = new Company();
        $this->userTypeModel         = new UserType();
        $this->userCompanyModel      = new UserCompany();
        $this->userLocationModel     = new UserLocation();
        $this->FinancialYearModel    = new FinancialYear();

        $this->helperPermission      = new HelperPermission();
    }

    /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Contracts\Support\Renderable
    */
    public function index(Request $request) {

        try {

            $id = 0;
            $roles = Role::where('isactive', 1)->pluck('name','id');
            $employees = $this->getEmployees($request);
            $companies = $this->getCompanies($request);
            $userTypes = $this->userTypeModel->getUserTypes($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->pluck('vname', 'id');
            $financialYears = $this->FinancialYearModel->getAccFinancialYears($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->pluck('vname', 'id');

            return view('users.create-user', compact('employees','roles', 'userTypes', 'companies', 'financialYears'));

        } catch (\Exception $e) {

            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    // ------ Fill Grid ------ //
    public function getUserList(Request $request) {

        $data = $this->userModel->getActiveUsers($request, 0, $this->utilsModel->CALL_TYPE_DEFAULT)->where('empisactive','=',1);
        return Datatables::of($data)
        ->addColumn('roles', function($data){

            $roles = $data->getRoleNames()->toArray();
            $badge = '';

            if($roles) {

                $badge = implode(' , ', $roles);
            }

            return $badge;
        })
        ->addColumn('action', function($data) {

            return '<div class="table-actions">
                <button id="editBtn" data="'.$data['id'].'" class="btn btn-info btn-icon"><i class="ik ik-edit"></i></button>

                <button id="deleteBtn" class="btn btn-danger btn-icon"  data="'.$data['id'].'" data-toggle="modal" data-target="#deleteModal"><i class="ik ik-trash-2"></i></button> 
                </div>';

        })
        ->rawColumns(['roles','permissions','action'])
        ->make(true);
    }

    public function fillForm(Request $request, $id) {

        try {

            $user = User::find($id);
            $request['userid'] = $id;

            if($user) {

                $roles = $user->getRoleIds()->toArray();
                
                $user_companies = $this->userCompanyModel->getUserCompanies($request, 0, $this->utilsModel->CALL_TYPE_DEFAULT);
                
                $user['user_companies'] = $user_companies->pluck('companyid');
                $user['user_company_ids'] = $user_companies->pluck('id');
                $user['user_role'] =$roles;             
                $user['user_password'] =$user->password;

                if (Hash::needsRehash($user->password)) {

                    $user->password = Hash::make('plain-text');
                    $user['hashed'] = $user->password;
                }

                return $user;
            }           

        } catch (\Exception $e) {

            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function getEmpData(Request $request){
       
        try {
            $empid = $request->empid;
            if ($empid) {
                $user = User::where('empid', $empid)->first();
                $request->request->add(['isactive' => '1']);
                $empdata = $this->employeeInfoModel->getEmployees($request, $empid, $this->utilsModel->CALL_TYPE_DEFAULT)->first()->toArray();
                if ($user == null) {
                    $user['employee'] = $empdata;
                    $user['user_companies'] = '';
                    $user['user_locations'] = '';
                    $user['user_company_ids'] = '';
                    $user['user_role'] ='';             
                    $user['user_password'] ='';
                    return  $user;
                }
                else{
                    $id = $user->id;
                    $request['userid'] = $id;
                    $roles = $user->getRoleIds()->toArray();
                    
                    $request['userid'] = $id;
                    $user_companies = $this->userCompanyModel->getUserCompanies($request, 0, $this->utilsModel->CALL_TYPE_DEFAULT);
                    $user_locations = $this->userLocationModel->getUserLocations($request, 0, $this->utilsModel->CALL_TYPE_DEFAULT);
                    $user['employee'] = $empdata;
                    $user['user_companies'] = $user_companies->pluck('companyid');
                    $user['user_locations'] = $user_locations->pluck('locationid');
                    $user['user_company_ids'] = $user_companies->pluck('id');
                    $user['user_role'] =$roles;             
                    $user['user_password'] =$user->password;

                    if (Hash::needsRehash($user->password)) {

                        $user->password = Hash::make('plain-text');
                        $user['hashed'] = $user->password;
                    }
                    return $user;                    
                }

            }

        } catch (\Exception $e) {

            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function edit($id) {
        try {

            $user = User::with('roles','permissions')->find($id);

            if($user) {

                $user_role = $user->roles->first();
                $roles     = Role::pluck('name','id');

                return view('users.user-edit', compact('user','user_role','roles'));

            } else {

                return redirect('404');
            }

        } catch (\Exception $e) {

            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function save(Request $request) {
        if(!$request->id){
            if (strlen($request->password) < 6) {
                return redirect()->back()->withInput($request->input())->with('error', 'Password must include at least 6 characters.');     
            }       
            if (strpos($request->password, '\'') !== false || strpos($request->password, '*') !== false || strpos($request->password, '-') !== false || strpos($request->password, ':') !== false || strpos($request->password, ';') !== false){
                return redirect()->back()->withInput($request->input())->with('error', 'These Special Characters ( /  \' - : ; *) are not allowded');
            }
        }
        $usercompanies = $request->companies == null ? array() : $request->companies;

        $oldAttachedUsercompnayids = $request->attachedComanyIds; // for update company ids
        $oldAttachedUsercompnayids = explode (",", $oldAttachedUsercompnayids[0]);

        $userCompaniesCount = isset($request->companies) ? count($usercompanies) : 0;
        $oldAttachedCompaniesCount = isset($request->attachedComanyIds) ? count($oldAttachedUsercompnayids) : 0;        

        $unionNewOldCompanyIds = $this->array_union($usercompanies, $oldAttachedUsercompnayids);

        array_unshift($usercompanies, "SpareForComparison");
        array_unshift($oldAttachedUsercompnayids, "SpareForComparison");

        $userCompanyRequest = Clone $request;
        $userCompanyRequest['soudu_name'] = $request->name;
        $userCompanyRequest['soudu_email'] = $request->email;
        $userCompanyRequest['soudu_phoneno'] = 123445;

        if($request->id) {

            $userCompanyRequest['userid'] = $request->id;
            $isDefault = 1;

            foreach ($unionNewOldCompanyIds as $companyId) {

                if (!is_numeric($companyId)) {

                    continue;
                }
                
                $userCompanyRequest["companyid"] = $companyId;
                $userCompanyRequest["isdefault"] = $isDefault;

                $userCompany = UserCompany::where('userid', $request->id)->where('companyid', $companyId)->first();

                if ($userCompany && array_search($companyId, $usercompanies)) {

                    $this->userCompanyModel->updateUserCompany($userCompanyRequest, $userCompany->id, $this->utilsModel->CALL_TYPE_DEFAULT);

                } else if ($userCompany) {

                    $userCompany->delete();

                } else {

                   $this->userCompanyModel->createUserCompany($userCompanyRequest, $this->utilsModel->CALL_TYPE_DEFAULT); 
                }

                $isDefault = 0;
            }
            $this->updateUserLocation($request, $request->id);
            return $this->update($request->id, $request);

        } else {

            $request->validate([
                'email' => 'required | email | unique:users',
            ]);
            $userCompanyRequest["isdefault"] = 1;
            $request['default_financialyearid'] = 1;
            $userId = $this->userModel->createUser($request, $this->userModel->CALL_TYPE_DEFAULT);
            $userCompanyRequest['userid'] = $userId;
            $this->updateUserLocation($request, $userId);
            foreach ($usercompanies as $usercompany) {

                if (!is_numeric($usercompany)) {

                    continue;
                }

                $userCompanyRequest["companyid"]= $usercompany;
                $this->userCompanyModel->createUserCompany($userCompanyRequest, $this->utilsModel->CALL_TYPE_DEFAULT);
            }

            return redirect('users')->with('success', 'User created succesfully!');
        }
    }

    public function update($id, Request $request) {
        $request['default_financialyearid'] = 1;
        return $this->userModel->updateUser($id, $request, $this->userModel->CALL_TYPE_DEFAULT);
    }


    public function delete($id) {

        return $this->userModel->deleteUser($id, $this->userModel->CALL_TYPE_DEFAULT);
    }

    public function updateUserLocation($request, $userId) {

        $userLocationRequest = Clone $request;
        $isDefault = 1;
        $userLocationRequest["isdefault"] = $isDefault;
        $locationId = $request->locationid;
        $userLocationRequest["locationid"] = $locationId;
        $userLocationRequest['userid'] = $userId;
        $companyId = $request->session()->get('companyid', 0);
        $userLocationRequest["companyid"] = $companyId;
        $userLocationRequest["vname"] = 'vname';
        $userLocationRequest["isactive"] = 1;
        if($request->id) {
            
            $userLocation = UserLocation::where('userid', $userId)->where('locationid', $locationId)->where('companyid', $companyId)->first();
            if ($userLocation) {
                    $this->userLocationModel->createUpdate($userLocationRequest, $userLocation->id, $this->utilsModel->SP_ACTION_UPDATE, $this->utilsModel->CALL_TYPE_DEFAULT);
            }
            else{
                $this->userLocationModel->createUpdate($userLocationRequest,0,$this->utilsModel->SP_ACTION_CREATE, $this->utilsModel->CALL_TYPE_DEFAULT);
            } 

        } else {
            $userLocation = UserLocation::where('userid', $userId)->where('locationid', $locationId)->where('companyid', $companyId)->first();
            if ($userLocation) {
                    $this->userLocationModel->createUpdate($userLocationRequest, $userLocation->id, $this->utilsModel->SP_ACTION_UPDATE, $this->utilsModel->CALL_TYPE_DEFAULT);
            }
            else{

                 $this->userLocationModel->createUserLocation($userLocationRequest, $this->utilsModel->CALL_TYPE_DEFAULT);
            } 
           
        }
    }

    public function getCompanies($request) {

        $companies = $this->companyModel->getCompanies($request, 0, $this->utilsModel->CALL_TYPE_DEFAULT);
        return $companies->pluck('vname', 'id');
    }

    public function getFinancialYears($request) {

        $financialYears = array();
        $financialYearsData = $this->FinancialYearModel->getAccFinancialYears($request, 0, $this->utilsModel->CALL_TYPE_DEFAULT);

        if (count($financialYearsData)) {

            $financialYears = $financialYearsData->pluck('vname', 'id');
        }

        return $financialYears;
    }

    public function getLocations($request) {

        $locations = array();
        $locationsData = $this->userLocationModel->getUserLocations($request, 0, $this->utilsModel->CALL_TYPE_DEFAULT);

        foreach ($locationsData as $location) {

            $locations[$location['id']] = $location['vname'];
        }

        return $locations;
    }


    public function changePassword(){

        return view('users.user_change_password');
    }

    public function changePasswordSave(Request $request){

        if (strlen($request->password) < 6 || strlen($request->password_confirmation) < 6 ) {
           return redirect()->back()->withInput($request->input())->with('error', 'Password must include at least 6 characters.');     
        }       
        if (strpos($request->password, '\'') !== false || strpos($request->password, '*') !== false || strpos($request->password, '-') !== false || strpos($request->password, ':') !== false || strpos($request->password, ';') !== false)
        {
             return redirect()->back()->withInput($request->input())->with('error', 'These Special Characters ( /  \' - : ; *) are not allowded');
        }
        if (strpos($request->password_confirmation, '\'') !== false || strpos($request->password_confirmation, '*') !== false || strpos($request->password_confirmation, '-') !== false || strpos($request->password_confirmation, ':') !== false || strpos($request->password_confirmation, ';') !== false)
        {
             return redirect()->back()->withInput($request->input())->with('error', 'These Special Characters ( /  \' - : ; *) are not allowded');
        }
        return $this->userModel->changePassword($request, $this->userModel->CALL_TYPE_DEFAULT);
    }

    public function array_union($x, $y) {

        $aunion=  array_merge(
            array_intersect($x, $y),
            array_diff($x, $y),     
            array_diff($y, $x)      
        );
        
        return $aunion;
    }
    public function getEmployees(Request $request, $id=0){
        $request->request->add(['isactive' => '1']); //add request
        $employeeInfoData = array();
        $employeeInfo = $this->employeeInfoModel->getEmployees($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        foreach ($employeeInfo as $value) {
             $employeeInfoData[$value['id']] = $value['employeename']." ".$value['fathername']." (".$value['empcode'] .") ";
        }
        natcasesort($employeeInfoData);
        return $employeeInfoData;
    }

}
