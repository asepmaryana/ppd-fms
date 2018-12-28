<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';

class OperationDistance extends REST_Controller 
{
	public function __construct() {
        parent::__construct();
        $this->load->model('OperationDistanceModel');
    }
	
	public function today_get(){
	    $crit  = ['reg_date'=>date('Y-m-d')];
	    $rows  = $this->OperationDistanceModel->get_count_bus($crit)->result();
	    $this->response($rows, REST_Controller::HTTP_OK);
	}
}
?>