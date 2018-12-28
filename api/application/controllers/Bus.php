<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';

class Bus extends REST_Controller 
{
	public function __construct() {
        parent::__construct();
        $this->load->model('BusModel');
        $this->load->model('RouteLineModel');
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
        
        $rows   = $this->BusModel->get_paged_list($crit, $sort, $order, $size, $offset)->result();
        $total  = $this->BusModel->get_count($crit);
        $totalPage  = ceil($total/$size);
        $firstPage  = ($page == 0 || $page == 1) ? true : false;
        $lastPage   = ($page == $totalPage) ? true : false;
        $response   = array('content'=>$rows, 'totalPage'=>$totalPage, 'first'=>$firstPage, 'last'=>$lastPage, 'page'=>intval($page), 'total'=>$total, 'crit'=>$crit);
        $this->response($response, REST_Controller::HTTP_OK);
    }
    
	public function lists_get() {
		$sort	= 'code';
		$order	= 'asc';
		$rows   = $this->BusModel->get_list([], $sort, $order)->result();
		$this->response($rows, REST_Controller::HTTP_OK);
	}
	
	public function save_post() {
		$values = json_decode(file_get_contents('php://input'), true);
		$values['code']   = $this->BusModel->get_auto();
		$values['connection_status_id']   = 2;
		$values['operation_status_id']    = 3;
		$values['id'] = $this->BusModel->save($values);
        $this->response($values, REST_Controller::HTTP_OK);
	}
	
	public function update_post() {
		$id    = $this->uri->segment(3);
        $values = json_decode(file_get_contents('php://input'), true);
        if(isset($values['service_group'])) unset($values['service_group']);
        if(isset($values['route_line'])) unset($values['route_line']);
        if(isset($values['manufacture_company'])) unset($values['manufacture_company']);
        if(isset($values['transport_company'])) unset($values['transport_company']);
        if(isset($values['service_status'])) unset($values['service_status']);
        if(isset($values['operation_status'])) unset($values['operation_status']);
        if(isset($values['connection_status'])) unset($values['connection_status']);
        $this->BusModel->update($id, $values);
        $this->response($values, REST_Controller::HTTP_OK);
	}
	
	public function delete_get() {
		$id    = $this->uri->segment(3);
		$this->BusModel->delete($id);
        $this->response([], REST_Controller::HTTP_OK);
	}
	
	public function trash_post() {
	    $values = json_decode(file_get_contents('php://input'), true);
	    $this->BusModel->removes($values);
	    $this->response([], REST_Controller::HTTP_OK);
	}
	
	public function status_get(){
	    $resp      = array();
	    $routes    = $this->RouteLineModel->get_list([], 'code', 'asc')->result();
	    $i=0;	    
	    foreach ($routes as $r) {
	        $resp[$i]  = ['code'=>$r->code, 'data'=>[
	            ['label'=>'Online', 'value'=>$this->BusModel->get_count(['route_line_id'=>$r->id, 'connection_status_id'=>1])],
	            ['label'=>'Offline', 'value'=>$this->BusModel->get_count(['route_line_id'=>$r->id, 'connection_status_id'=>2])]
	        ]];
	        $i++;
	    }
	    $this->response($resp, REST_Controller::HTTP_OK);
	}
	
	public function info_get(){
	    $id    = $this->uri->segment(3);
	    $bus   = $this->BusModel->get_by_id($id)->row();
	    $this->response($bus, REST_Controller::HTTP_OK);
	}
}
?>