<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables,Auth;
use DB;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Helper\Permission as HelperPermission;

class PermissionController extends Controller {

    public $helperPermission;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {

        $this->middleware('auth');
        $this->helperPermission = new HelperPermission();
    }

    /**
     * Show the roles page
     *
     */
    public function index() {

        try {

            $roles = Role::pluck('name','id');

            return view('permission', compact('roles'));
        
        } catch (\Exception $e) {
            
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Show the role list with associate permissions.
     * Server side list view using yajra datatables
     */
    public function fillGrid(Request $request) {

        $data  = Permission::get();

        return Datatables::of($data)
        ->addColumn('action', function($data){

            if (Auth::user()->can($this->helperPermission->MANAGE_PERMISSION)) {

                return '<div class="table-actions">
                <button id="deleteBtn" class="btn btn-danger btn-icon"  data="'.$data['id'].'" data-toggle="modal" data-target="#deleteModal"><i class="ik ik-trash-2"></i></button>  
                </div>';

            } else {

                return '';
            }
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    /**
     * Store new roles with assigned permission
     * Associate permissions will be stored in table
     */
    public function fillForm(Request $request){

        // update permission table
        $roles = array();
        $data = array();
        $permission = Permission::find($request->id);

        if($permission) {

            $permission_name = $permission->name;
            //$this->index($permission_name);
            $assignRoleIds   = $permission->roles()->pluck('role_id');

            if ($assignRoleIds) {

                foreach ($assignRoleIds as $key => $id) {

                    $rolename = Role::find($id);
                    array_push($roles , $rolename->id);
                }
            }

            $data['permission_name'] = $permission_name;
            $data['roles'] = $roles;

            return $data;

        } else {

            return redirect('404');
        }
    }

    public function create(Request $request) {

        $validator = Validator::make($request->all(), [
            'permission' => 'required'
        ]);
        
        if ($validator->fails()) {

            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }

        try {

            $permission = Permission::create(['name' => $request->permission]);
            $permission->syncRoles($request->roles);

            if($permission) { 

                return redirect('permission')->with('success', 'Permission created succesfully!');

            } else {

                return redirect('permission')->with('error', 'Failed to create permission! Try again.');
            }

        } catch (\Exception $e) {

            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function update(Request $request) {

        // update permission table
        $permission = Permission::find($request->id);
        $permission->name = $request->name;
        $permission->save();

        return $permission;
    }

    public function delete($id) {

        $permission = Permission::find($id);

        if($permission) {

            $delete = $permission->delete();
            $perm   = $permission->roles()->delete();

            return redirect('permission')->with('success', 'Permission deleted!');

        } else {

            return redirect('404');
        }
    }

    public function getPermissionBadgeByRole(Request $request) {

        $badges = '';

        if ($request->id) {

            $role = Role::find($request->id);

            if($role->name == 'Super Admin'){

                $badges = 'Super Admin has all the permissions!';

            } else {

                $permissions =  $role->permissions()->pluck('name','permissions.id');
                foreach ($permissions as $key => $permission) {

                    $badges .= '<span class="badge badge-dark m-1">'.$permission.'</span>';
                }
            }
        }

        return $badges;
    }
}
