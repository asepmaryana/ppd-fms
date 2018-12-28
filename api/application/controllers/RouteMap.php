<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';

class RouteMap extends REST_Controller 
{
	public function __construct() {
        parent::__construct();
        $this->load->model('RouteMapModel');
        $this->load->model('RouteMapLineModel');
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
        
        $sort	= 'id';
        $order	= 'asc';
        
        $rows   = $this->RouteMapModel->get_paged_list($crit, $sort, $order, $size, $offset)->result();
        $total  = $this->RouteMapModel->get_count($crit);
        $totalPage  = ceil($total/$size);
        $firstPage  = ($page == 0 || $page == 1) ? true : false;
        $lastPage   = ($page == $totalPage) ? true : false;
        $response   = array('content'=>$rows, 'totalPage'=>$totalPage, 'first'=>$firstPage, 'last'=>$lastPage, 'page'=>intval($page), 'total'=>$total, 'crit'=>$crit);
        $this->response($response, REST_Controller::HTTP_OK);
    }
    
	public function lists_get() {
		$sort	= 'id';
		$order	= 'asc';
		$rows   = $this->RouteMapModel->get_list([], $sort, $order)->result();
		$this->response($rows, REST_Controller::HTTP_OK);
	}
	
	public function save_post() {
		$values = json_decode(file_get_contents('php://input'), true);
		$route_map_line   = [];
		if(isset($values['lines'])) {
		    $route_map_line = $values['lines'];
		    unset($values['lines']);
		}
		$values['id'] = $this->RouteMapModel->save($values);
		
		foreach ($route_map_line as $line_id) $this->RouteMapLineModel->save(['route_map_id'=>$values['id'], 'line_id'=>$line_id]);
		
        $this->response($values, REST_Controller::HTTP_OK);
	}
	
	public function update_post() {
		$id    = $this->uri->segment(3);
        $values = json_decode(file_get_contents('php://input'), true);
        $route_map_line   = [];
        if(isset($values['lines'])) {
            $route_map_line = $values['lines'];
            unset($values['lines']);
        }
        unset($values['service_group']);
        unset($values['service_status']);
        $this->RouteMapModel->update($id, $values);
        
        if(count($route_map_line) > 0) {
            $this->RouteMapLineModel->delete_by_route_map_id($id);
            foreach ($route_map_line as $line_id) $this->RouteMapLineModel->save(['route_map_id'=>$values['id'], 'line_id'=>$line_id]);
        }
        
        $this->response($values, REST_Controller::HTTP_OK);
	}
	
	public function delete_get() {
		$id    = $this->uri->segment(3);
		$this->RouteMapModel->delete($id);
        $this->response([], REST_Controller::HTTP_OK);
	}
	
	public function trash_post() {
	    $values = json_decode(file_get_contents('php://input'), true);
	    $this->RouteMapModel->removes($values);
	    $this->response([], REST_Controller::HTTP_OK);
	}
	
	public function line_get() {
	    $route_map_id    = $this->uri->segment(3);
	    $this->response($this->RouteMapLineModel->get_by_route_map_id($route_map_id)->result(), REST_Controller::HTTP_OK);
	}
}
?>