<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';

class Driver extends REST_Controller 
{
	public function __construct() {
        parent::__construct();
        $this->load->model('DriverModel');
    }
	
    function list_get()
    {
        $name           = $this->uri->segment(3);
        $page           = $this->uri->segment(4);
        $size           = $this->uri->segment(5);
        
        if(empty($page) || $page == '0') $page = 1;
        if(empty($size) || $size == '0') $size = 10;
        $offset         = ($page-1)*$size;
        $name		    = str_replace('_', ' ', $name);
        
        $crit           = array();
        $crit['name']   = trim($name);
        
        $sort	= 'code';
        $order	= 'asc';
        
        $rows   = $this->DriverModel->get_paged_list($crit, $sort, $order, $size, $offset)->result();
        $total  = $this->DriverModel->get_count($crit);
        $totalPage  = ceil($total/$size);
        $firstPage  = ($page == 0 || $page == 1) ? true : false;
        $lastPage   = ($page == $totalPage) ? true : false;
        $response   = array('content'=>$rows, 'totalPage'=>$totalPage, 'first'=>$firstPage, 'last'=>$lastPage, 'page'=>intval($page), 'total'=>$total, 'crit'=>$crit);
        $this->response($response, REST_Controller::HTTP_OK);
    }
    
	public function lists_get() {
		$sort	= 'code';
		$order	= 'asc';
		$rows   = $this->DriverModel->get_list([], $sort, $order)->result();
		$this->response($rows, REST_Controller::HTTP_OK);
	}
	
	public function save_post() {
		$values = json_decode(file_get_contents('php://input'), true);
		$values['code']   = $this->DriverModel->get_auto();
		$values['id'] = $this->DriverModel->save($values);
        $this->response($values, REST_Controller::HTTP_OK);
	}
	
	public function update_post() {
		$id    = $this->uri->segment(3);
        $values = json_decode(file_get_contents('php://input'), true);
        if(isset($values['service_group'])) unset($values['service_group']);
        if(isset($values['transport_company'])) unset($values['transport_company']);
        if(isset($values['service_status'])) unset($values['service_status']);
        $this->DriverModel->update($id, $values);
        $this->response($values, REST_Controller::HTTP_OK);
	}
	
	public function delete_get() {
		$id    = $this->uri->segment(3);
		$this->DriverModel->delete($id);
        $this->response([], REST_Controller::HTTP_OK);
	}
	
	public function trash_post() {
	    $values = json_decode(file_get_contents('php://input'), true);
	    $this->DriverModel->removes($values);
	    $this->response([], REST_Controller::HTTP_OK);
	}
}
?>