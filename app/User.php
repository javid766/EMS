<?php


namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\User\UserLocation;
use App\Models\User\UserCompany;

use App\Models\Utils;
use App\Models\HrPayroll\Setup\Tenant;

use Auth;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'empid', 'tid', 'name', 'email', 'password', 'allowaudit', 'allowactual', 'isadmin', 'usertype', 'isindividual', 'default_companyid', 'default_financialyearid', 'usersec_flage', 'macaddress', 'computer_name', 'window_loginname', 'isactive', 'insertedby', 'inserteddate', 'insertedip', 'updatedby', 'updateddate', 'updatedip', 'createdat', 'updatedat',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $timestamps = false;

    public $PAGE_LINK;

    public $PAGE_LINK_PASSWORD;

    public $utilsModel;

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);

        $this->utilsModel = new Utils();

        $this->PAGE_LINK = route('users-index');
        $this->PAGE_LINK_PASSWORD = route('change-password');
    }

    public function get_roles(){

        $roles = [];
        foreach ($this->getRoleNames() as $key => $role) {

            $roles[$key] = $role;
        }

        return $roles;
    }

    public function locations() {

        return $this->hasMany(UserLocation::class, 'userid');
    }

    public function companies() {

        return $this->hasMany(UserCompany::class, 'userid');
    }

    public function isSuperAdmin() {

        foreach ($this->getRoleNames() as $role) {

            if ($role == "Super Admin" || $role == "Admin") {
                return true;    
            }
        }
        
        return false;
    }

    public function isTenant() {

        $user = Auth::user();

        $email = $user->email;

        $tenant = Tenant::select('tpassword')->where('tlogin', $email)->first();

        if ($tenant) {
            
            return true;
        }

        return false;
    }

    public function getUsers($id, $request, $type) {

        $validator = Validator::make($request->all() ,[
            'tid' => 'required | integer',
        ]);
        
        if($validator->fails()) {

            return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
        }

        $users = User::where('tid', $request->tid);

        if ($id > 0) {
            $users->where('id', $id);
        }

        $users = $users->get();

        return response([
            'users' => $users,
            'status'=> 'success',
        ]);

    }

    public function getActiveUsers($request, $id, $type) {

        if ($type == $this->utilsModel->CALL_TYPE_API) {

            $validator = Validator::make($request->all() ,[
                'companyid' => 'required | integer',
                'locationid' => 'required | integer',
            ]);

            if($validator->fails()) {

                return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
            }

            $companyid = $request->companyid;
            $locationid = $request->locationid;

        } else {

            $companyid = $request->session()->get('companyid', 0);
            $locationid = $request->session()->get('locationid', 0);
        }

        $users = User::hydrate(
            DB::select('CALL sp_users_get('. $id .', '. $companyid .', '. $locationid .')')
        );

        if ($type == $this->utilsModel->CALL_TYPE_API) {

            return response([
                'users' => $users,
                'status' => 'success'
            ]);

        } else {

            return $users;
        }

    }

    public function createUser($request, $type) {

        $validator = Validator::make($request->all(), [
            'empid'            => 'required',
            'name'             => 'required | string ',
            'email'            => 'required | email | unique:users',
            'password'         => 'required',
            'roleid'           => 'required',
            'allowaudit'       => 'integer ',
            'allowactual'      => 'integer ',
            'isadmin'          => 'integer ',
            'usertype'         => 'required | integer ',
            'usersec_flage'    => 'integer',
            'isactive'         => 'integer',
            'loggedinuserid'   => isset($request->loggedinuserid) ? 'required | integer' : '',
        ]);
        
        if($validator->fails()) {

            return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
        }

        try {

            $macAddr = exec('getmac');
            $computerName = 'Web';
            $windowLoginName = PHP_OS;

            $tid = $type == $this->utilsModel->CALL_TYPE_API ? $request->tid : Auth::user()->tid;
            $insertedBy = $type == $this->utilsModel->CALL_TYPE_API ? $request->loggedinuserid : Auth::user()->id;
            $companyid = $request->session()->get('companyid', 0);


            // store user information
            $user = User::create([
                'empid'                   => $request->empid,
                'tid'                     => $tid,
                'name'                    => $request->name,
                'email'                   => $request->email,
                'password'                => Hash::make($request->password),
                'allowaudit'              => isset($request->allowaudit) ? $request->allowaudit : 0,
                'allowactual'             =>(isset($request->allowactual) ? $request->allowactual : 0) ,
                'isadmin'                 => (isset($request->isadmin) ? $request->isadmin : 0) ,
                'usertype'                => $request->usertype,
                'isindividual'            => isset($request->isindividual) ? $request->isindividual : 0,
                'default_companyid'       => $companyid,
                'default_financialyearid' => $request->default_financialyearid,
                'usersec_flage'           => $request->usersec_flage,
                'macaddress'              => $macAddr,
                'computer_name'           => $computerName,
                'window_loginname'        => $windowLoginName,
                'isactive'                =>(isset($request->isactive) ? $request->isactive : 0),
                'insertedby'              => $insertedBy, // Logged in user id
                'inserteddate'            => now(),
                'insertedip'              => $request->ip(),
                'updatedby'               => 0,
                'updateddate'             => now(),
                'updatedip'               => 'NULL',
            ]);

            // assign new role to the user
            $user->syncRoles($request->roleid);

            DB::table('model_has_roles')
            ->where('role_id', $request->roleid)
            ->where('model_id', $user->id)
            ->update([
                'isactive'      => 1,
                'insertedby'   => $insertedBy,
                'inserteddate' => now(),
            ]);

            $message = $user ? "User created succesfully!" : "Sorry! Failed to create user!" ;
            $status = $user ? "success" : "error" ;
           return $user->id;
            //return $this->utilsModel->returnResponseStatusMessage($status, $message, $type, $this->PAGE_LINK);

        } catch (\Exception $e) {

            $bug = $e->getMessage();

            return $this->utilsModel->returnResponseStatusMessage('error', $bug, $type, $this->PAGE_LINK);
        }
    }

    public function updateUser($id, $request, $type) {

        $validator = Validator::make($request->all(), [
            
            'loggedinuserid' => isset($request->loggedinuserid) ? 'required | integer' : '',
        ]);


        if($validator->fails()) {

            return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK);
        }

        $user = User::find($id);

        $message = "";
        $status = "";

        if (!$user) {

            return $this->utilsModel->returnResponseStatusMessage("error", "User does not exist!", $type, $this->PAGE_LINK);
        }

        $updatedBy = $type == $this->utilsModel->CALL_TYPE_API ? $request->loggedinuserid : Auth::user()->id;
        $companyid = $request->session()->get('companyid', 0);

        try {

            $user->update([
                'empid'                   => isset($request->empid) ? $request->empid : $user->empid,
                'name'                    => isset($request->name) ? $request->name : $user->name,
                'allowaudit'              => isset($request->allowaudit) ? $request->allowaudit : 0,
                'allowactual'             => isset($request->allowactual) ? $request->allowactual : 0,
                'isadmin'                 => isset($request->isadmin) ? $request->isadmin : 0,
                'usertype'                => isset($request->usertype) ? $request->usertype : $user->usertype,
                'isindividual'            => isset($request->isindividual) ? $request->isindividual : 0,
                'default_companyid'       => $companyid,
                'default_financialyearid' => isset($request->default_financialyearid) ? $request->default_financialyearid: $user->default_financialyearid,
                'usersec_flage'           => isset($request->usersec_flage) ? $request->usersec_flage: $user->$request->usersec_flage,
                'isactive'                => isset($request->isactive) ? $request->isactive : 0 ,
                'updatedby'               => $updatedBy, // Logged in user id
                'updateddate'             => now(),
                'updatedip'               => $request->ip(),
            ]);
 
            if (isset($request->roleid))
                $user->syncRoles($request->roleid);

            $message = 'User information updated succesfully!';

            return $this->utilsModel->returnResponseStatusMessage('success', $message, $type, $this->PAGE_LINK);

        } catch (\Exception $e) {

            $bug = $e->getMessage();

            return $this->utilsModel->returnResponseStatusMessage('error', $bug, $type, $this->PAGE_LINK);
        }
    }

    public function deleteUser($id, $type) {

        $user = User::find($id);
        $message = "";
        $status = "";

        if($user){
            $user->delete();

            $status = "success";
            $message = "User has been deleted";

        } else {

            $status = "error";
            $message = "User does not exist!";
        }

        return $this->utilsModel->returnResponseStatusMessage($status, $message, $type, $this->PAGE_LINK);
    }

    public function changePassword($request, $type) {

        $validator = Validator::make($request->all(), [
            'oldpassword' => 'required | string',
            'password_confirmation' => 'required | string',
            'password' => 'required | string | confirmed'
        ]);

        if($validator->fails()) {

            return $this->utilsModel->returnResponseStatusMessage('error', $validator->messages()->first(), $type, $this->PAGE_LINK_PASSWORD);
        }

        // match old password
        if (Hash::check($request->oldpassword, Auth::user()->password)){

            $user = auth()->user();

            $hashedPassword = Hash::make($request->password);
            
            User::find($user->id)
            ->update([
                'password'=> $hashedPassword
            ]);

            $tenant = Tenant::where('tlogin', $user->email)->first();
            
            if ($tenant) {

                $tenant->update([
                    'tpassword'=> $hashedPassword
                ]);
            }

            return $this->utilsModel->returnResponseStatusMessage('success', 'Password has been changed', $type, $this->PAGE_LINK_PASSWORD);
        }

        return $this->utilsModel->returnResponseStatusMessage('error', 'Password not matched!', $type, $this->PAGE_LINK_PASSWORD);
    }

    public static function getUserSessionFields($request, $user) {

        $userData = DB::select('CALL sp_user_login_get('. $user->id .', "'. $user->email .'", "'. $user->password .'", "", "'. $request->ip() .'")');

        return $userData;
    }

    public static function getUserMainMenu($user) {

        $userid = $user->isSuperAdmin() ? 0 : $user->id;

        $userMenu = DB::select('CALL sp_main_menu_get('. $userid .')');

        return $userMenu;
    }

    public static function saveUserDataToSession($request, $user) {

        $userData = self::getUserSessionFields($request, $user);
        if (count($userData) > 0) {
            
            $userDataArr = (array)$userData[0];

            foreach ($userDataArr as $key => $value) {
                $request->session()->put($key, $value);
            }
        }
    } 
}
