<?php

namespace App\Http\Controllers\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\Location;
use App\Models\HrPayroll\Setup\Country;
use App\Models\Utils;
use App\User;
use DataTables,Auth;

class LocationController extends Controller
{
    public $location;
    public $utilsModel;
    public $setupCountryModel;

    public function __construct() {

        $this->location = new Location();
        $this->setupCountryModel = new Country();
        $this->utilsModel = new Utils();

    }

    public function index(Request $request){

        $countries = $this->getCountries($request);
        return view('hrpayroll.setup.location',compact('countries'));
    }

    public function getData($request, $id=0){
        
        return $this->location->getLocations($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);    
    }

    public function fillGrid(Request $request){
        
        $modelData=$this->getData($request);
        return Datatables::of($modelData)
         ->addColumn('action', function($data){
            return ('<div class="table-actions">
                <button id="editBtn" data="'.$data['id'].'" class="btn btn-info btn-icon"><i class="ik ik-edit"></i></button>

                <button id="deleteBtn" class="btn btn-danger btn-icon"  data="'.$data['id'].'" data-toggle="modal" data-target="#deleteModal"><i class="ik ik-trash-2"></i></button> 
                </div>');
        })
        ->rawColumns(['action'])
        ->make(true);
    }

    public function fillForm(Request $request, $id){

        $modelData=$this->getData($request, $id)[0];
        if($modelData){
            return response($modelData);
        }
        else{
            return response(array(
                'error' => 1,
                'data' => 'Location doesn\'t exists!!',
            ));
        }
    }

    public function save(Request $request){
        
        $location = Location::where('id', '<>', $request->id)->where('vcode', trim($request->vcode))->orwhere('vname', trim($request->vname))->where('id', '<>', $request->id)->first();
        
        if ($location) {

            return redirect()->back()->withInput($request->input())->with('error', 'Code/Title already exists.');
        }

        if($request->id){
            
            $id = $request->id;

            return $this->location->updateLocation($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        
        } else {

            return $this->location->createLocation($request, $this->utilsModel->CALL_TYPE_DEFAULT);
        }
    }

    public function delete($id){

        return $this->location->deleteLocation($id, $this->utilsModel->CALL_TYPE_DEFAULT);
    }

    public function getCountries($request){

        $countriesData=$this->setupCountryModel->getCountries($request, 0, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($countriesData);
        return $countriesData;
    }
}
