<?php

namespace App\Http\Controllers;

use DataTables,Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use App\Helper\Permission as HelperPermission;

class RolesController extends Controller {

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

            // if (Auth::user()->isSuperAdmin()) {

                $webpermissions = Permission::where('isactive', 1)->where('parentid','=', 0)->where('interface','=', 'web')->where('srno','<>', NULL)->orderBy('srno', 'ASC')->orderBy('parentid', 'ASC')->get();
            
            // } else {

            //     $webpermissions = Permission::where('name', 'not like', '%tenant%')->where('parentid','=', 0)->where('interface','=', 'web')->where('srno','<>', NULL)->where('isactive', 1)->orderBy('srno', 'ASC')->orderBy('parentid', 'ASC')->get();
            // }

            return view('roles.index', compact('webpermissions'));
        
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

        if (Auth::user()->isSuperAdmin()) {

            $data = Role::get();
                
        } else {

            $data = Role::where('tid', Auth::user()->tid)->where('roles.name', 'NOT LIKE', '%Tenant%')->get();
        }
         $data = Role::get();
        return Datatables::of($data)
        ->addColumn('permissions', function($data) {

            $roles = $data->permissions()->get()->where('srno','<>', NULL);
            $badges = '';
            $count = 1;
            foreach ($roles as $key => $role) {

                if ($count <= 4) {

                    $badges .= '<span class="badge badge-dark m-1">'.ucfirst(str_replace('_', ' ', $role->name)).'</span>';
                }

                if ($count == 4) {

                    $badges .= ' <a class="view-all" href="'.url('role/edit/'.$data->id).'">View All</a>';
                }

                if ($count > 4) {

                    break;
                }

                $count++;
            }

            if ($data->name == 'Admin'){
                return '<span class="badge badge-success m-1">All permissions</span>';
            }

            return $badges;
        })
        ->addColumn('action', function($data) {

            if ($data->name == 'Admin') {

                return '';
            }

            if (Auth::user()->can($this->helperPermission->MANAGE_ROLE)) {

                return '<div class="table-actions">  
                <a class="btn grid-action-btns btn-info btn-icon" id ="editBtn" href="'.url('role/edit/'.$data->id).'"><i class="ik ik-edit"></i></a>
                <a class="btn grid-action-btns btn-danger btn-icon" id ="deleteBtn" href="#" data="'.$data->id.'" data-toggle="modal" data-target="#deleteModal"><i class="ik ik-trash-2"></i></a>
                </div>';

            } else {

                return '';
            }
        })
        ->rawColumns(['permissions','action'])
        ->make(true);
    }

    /**
     * Store new roles with assigned permission
     * Associate permissions will be stored in table
     */
    public function save(Request $request) {

        // laravel default validator
        $validator = Validator::make($request->all(), [
            'role' => 'required'
        ]);
        
        if ($validator->fails()) {

            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }

        try {

            $role = Role::create([
                'name' => $request->role,
                'isactive' => (isset($request->isactive) ? 1 : 0),
            ]);

            if ($request->webpermissions != null) {
                $role->syncPermissions($request->webpermissions);
            }

            if ($role) { 

                return redirect('roles')->with('success', 'Role created succesfully!');

            } else {

                return redirect('roles')->with('error', 'Failed to create role! Try again.');
            }

        } catch (\Exception $e) {

            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    public function edit($id) {
        $role  = Role::where('id',$id)->first();
        // if role exist
        if ($role) {
            $role_permission = $role->permissions()
            ->pluck('permissions.id')
            ->toArray();

            if (Auth::user()->isSuperAdmin()) {
                $webpermissions = Permission::select('name','id','parentid')->where('isactive', 1)->where('interface','=', 'web')->where('srno','<>', NULL)->where('parentid','=', 0)->orderBy('srno', 'ASC')->orderBy('parentid', 'ASC')->get();          
            } else {
                $webpermissions = Permission::select('name','id','parentid')->where('name', 'not like', '%tenant%')->where('isactive', 1)->where('interface','=', 'web')->where('srno','<>', NULL)->where('parentid','=', 0)->orderBy('srno', 'ASC')->orderBy('parentid', 'ASC')->get();
            }
            return view('roles.edit-roles', compact('role','role_permission','webpermissions'));
        } else {
            return redirect('404');
        }
    }

    public function update(Request $request) {
        // update role
        $validator = Validator::make($request->all(), [
            'role' => 'required',
            'id'   => 'required'
        ]);
        
        if ($validator->fails()) {

            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }
        try {
            $role = Role::find($request->id);

            $update = $role->update([
              'name' => $request->role,
              'isactive' => (isset($request->isactive) ? 1 : 0),
            ]);

            // Sync role permissions
            if ($request->webpermissions != null) {
                $role->syncPermissions($request->webpermissions);
            }

            return redirect('roles')->with('success', 'Role info updated succesfully!');

        } catch (\Exception $e) {

            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

     public function delete($id) {

        $role = Role::find($id);
        $rolename = $role->name;
        $userrole= User::role($rolename)->get();
        
        if(count($userrole)>0){
            return redirect('roles')->with('error', 'Cannot Delete! Role is in use!');

        } else {

            $delete = $role->delete();
            $perm   = $role->permissions()->delete();
            return redirect('roles')->with('success', 'Role deleted!');
        }

    }
}
