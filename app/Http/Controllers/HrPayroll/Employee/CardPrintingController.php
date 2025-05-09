<?php

namespace App\Http\Controllers\HrPayroll\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HrPayroll\Setup\Dept;
use App\Models\HrPayroll\Setup\EType;
use App\Models\HrPayroll\Setup\Location;
use App\Models\HrPayroll\Employee\EmployeeInfo;
use App\Models\HrPayroll\Employee\CardPrinting;
use App\Models\Utils;
use App\User;
use DataTables, Auth, \PDF, Response;
use LynX39\LaraPdfMerger\Facades\PdfMerger;

class CardPrintingController extends Controller
{
    public $deptModel;
    public $locationModel;
    public $eTypeModel;
    public $employeeInfoModel;
    public $cardPrintingModel;
    public $utilsModel;

    public function __construct() {
        $this->deptModel = new Dept();
        $this->locationModel = new Location();
        $this->eTypeModel = new EType();
        $this->employeeInfoModel  = new EmployeeInfo();
        $this->cardPrintingModel = new CardPrinting();
        $this->utilsModel = new Utils();
    }

    public function index(Request $request){
        $allDepts = $this->getDepartments($request);
        $allLocations  = $this->getLocations($request);
        $allEtypes  = $this->getETypes($request);
        return view('hrpayroll.employee.card_printing',compact('allDepts','allLocations','allEtypes'));
    }


    public function fillGrid(Request $request, $deptid, $etypeid,$locationid, $id=0){
        $request->request->add(['isactive' => '1']); //add request
        $modelData = $this->employeeInfoModel->getEmployees($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('locationid' , $locationid)->where('deptid' , $deptid)->where('etypeid' , $etypeid);
        $modelData = $modelData->sort();
        return Datatables::of($modelData)
        ->addColumn('checkboxes', function($data){
            return '<input type="checkbox" name="dtcheckbox" class="dtcheckbox"/>';                
        })
        ->rawColumns(['checkboxes'])
        ->make(true);
    }

    public function fill(Request $request, $id=0){
        if(!$request->deptid){
            $request['deptid'] = 0;
        }
        if(!$request->locationid){
            $request['locationid'] = 0;
        }
        if(!$request->etypeid){
            $request['etypeid'] = 0;
        }
        if($request->alldepts == 'false'){
            $request['alldepts'] = 0;
        }
        else if($request->alldepts == 'true'){
            $request['alldepts'] = 1;
        }

        $modelData = $this->cardPrintingModel->getEmployees($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT);
        return Datatables::of($modelData)
        ->addColumn('checkboxes', function($data){
            return '<input type="checkbox" name="dtcheckbox" class="dtcheckbox"/>';                
        })
        ->rawColumns(['checkboxes'])
        ->make(true);
    }

    public function setEmpIdsInSession(Request $request, $id=0){
        $cardprinting_empids = $request->selectedCells;
        $request->session()->put('cardprinting_empids', $cardprinting_empids);
    }

    public function print(Request $request, $id=0){

        $files = glob(base_path('cardprinting_pdfs/*'));
        
        foreach($files as $file){
        
            if(is_file($file)) {
        
                unlink($file);
            }
        }
        
        $pdfMerger   = PDFMerger::init();
        $empids = $request->session()->get('cardprinting_empids');
        $request->request->add(['empids' => $empids]); //add request
        $empData = $this->cardPrintingModel->getEmployeesCardData($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->toArray();
        $empcode = array_column($empData, 'empcode');

        array_multisort($empcode, SORT_ASC, $empData);

        $num   = sizeof($empData);
        $data = array();
        
        if ($num > 0 && $num > 4) {
           
           $count = 0; 
           $i = 1;
           
           foreach ($empData as $key => $value) {

                $filename = 'empcard'.'-'.$i.'.pdf';
            
                if ($count != 4) {
            
                    $data[$i][$key] = $value;
                }
            
                if ($count == 4) {
            
                    $carddata = ['empcarddata' => $data[$i]];
                    $pdf = PDF::loadView('hrpayroll.employee.cardprinting.cardprinting_pdf', $carddata);
                    $pdf->save(base_path('cardprinting_pdfs/'.$filename));
                    $i++;
                    $count = -1;
                    $data = array();
                }
                
                $count++;
            }
            
            $files = array_diff(scandir(base_path('cardprinting_pdfs/')), array('..', '.'));
            
            natsort($files);
            
            foreach ($files as $file) {
            
                $pdfMerger->addPDF(base_path('cardprinting_pdfs/'.$file), 'all');
            }
            
            $pdfMerger->merge();
            $pdfMerger->save(base_path('cardprinting_pdfs/empcardprintingfinal.pdf'), "file");
            $filename = 'empcardprintingfinal.pdf';
            $path = base_path('cardprinting_pdfs/'.$filename);
            
            return Response::make(file_get_contents($path), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$filename.'"'
            ]);

        } else {

            $carddata = ['empcarddata' => $empData];
            $pdf = PDF::loadView('hrpayroll.employee.cardprinting.cardprinting_pdf', $carddata);
            $filename = 'empcardprintingfinal.pdf';
            $pdf->save(base_path('cardprinting_pdfs/'.$filename));   
            $path = base_path('cardprinting_pdfs/'.$filename);
            
            return Response::make(file_get_contents($path), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$filename.'"'
            ]);
        }
        
    }

    public function getDepartments(Request $request, $id=0){
        $deptsData = $this->deptModel->getDepts($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($deptsData); 
       return $deptsData;
    }

    public function getLocations(Request $request, $id=0){
        $locationData = $this->locationModel->getLocations($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($locationData); 
       return $locationData;
    }

    public function getETypes(Request $request, $id=0){
        $eTypeData = $this->eTypeModel->getETypes($request, $id, $this->utilsModel->CALL_TYPE_DEFAULT)->where('isactive','=', 1)->pluck('vname','id')->toArray();
        natcasesort($eTypeData); 
       return $eTypeData;
    }

}
