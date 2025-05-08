<?php

namespace App\Http\Controllers\Api\HrPayroll\Setup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HrPayroll\Setup\Announcement;
use App\Models\Utils;

class AnnouncementController extends Controller {
    
    public $announcementModel;
	public $utilsModel;

	public function __construct() {

		$this->announcementModel = new Announcement();
		$this->utilsModel = new Utils();
	}

    public function list(Request $request, $id = 0) {

    	return $this->announcementModel->getAnnouncements($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function create(Request $request) {

    	return $this->announcementModel->createAnnouncement($request, $this->utilsModel->CALL_TYPE_API);
    }

    public function update(Request $request, $id) {

    	return $this->announcementModel->updateAnnouncement($request, $id, $this->utilsModel->CALL_TYPE_API);
    }

    public function delete($id) {
    	
    	return $this->announcementModel->deleteAnnouncement($id, $this->utilsModel->CALL_TYPE_API);
    }
}
