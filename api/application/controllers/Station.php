<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';

class Station extends REST_Controller 
{
	public function __construct() {
        parent::__construct();
        $this->load->model('StationModel');
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
        
        $rows   = $this->StationModel->get_paged_list($crit, $sort, $order, $size, $offset)->result();
        $total  = $this->StationModel->get_count($crit);
        $totalPage  = ceil($total/$size);
        $firstPage  = ($page == 0 || $page == 1) ? true : false;
        $lastPage   = ($page == $totalPage) ? true : false;
        $response   = array('content'=>$rows, 'totalPage'=>$totalPage, 'first'=>$firstPage, 'last'=>$lastPage, 'page'=>intval($page), 'total'=>$total, 'crit'=>$crit);
        $this->response($response, REST_Controller::HTTP_OK);
    }
    
	public function lists_get() {
		$sort	= 'code';
		$order	= 'asc';
		$rows   = $this->StationModel->get_list([], $sort, $order)->result();
		$this->response($rows, REST_Controller::HTTP_OK);
	}
	
	public function save_post() {
		$values = json_decode(file_get_contents('php://input'), true);
		$values['code']   = $this->StationModel->get_auto();
		$values['id'] = $this->StationModel->save($values);
        $this->response($values, REST_Controller::HTTP_OK);
	}
	
	public function update_post() {
		$id    = $this->uri->segment(3);
        $values = json_decode(file_get_contents('php://input'), true);
        if(isset($values['route_line'])) unset($values['route_line']);
        if(isset($values['station_direct'])) unset($values['station_direct']);
        if(isset($values['station_type'])) unset($values['station_type']);
        if(isset($values['ad_screen'])) unset($values['ad_screen']);
        if(isset($values['service_group'])) unset($values['service_group']);
        if(isset($values['service_status'])) unset($values['service_status']);
        $this->StationModel->update($id, $values);
        $this->response($values, REST_Controller::HTTP_OK);
	}
	
	public function delete_get() {
		$id    = $this->uri->segment(3);
		$this->StationModel->delete($id);
        $this->response([], REST_Controller::HTTP_OK);
	}
	
	public function trash_post() {
	    $values = json_decode(file_get_contents('php://input'), true);
	    $this->StationModel->removes($values);
	    $this->response([], REST_Controller::HTTP_OK);
	}
	
	public function info_get(){
	    $id    = $this->uri->segment(3);
	    $station   = $this->StationModel->get_by_id($id)->row();
	    $this->response($station, REST_Controller::HTTP_OK);
	}
}
?>