<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';

class SpeedViolationLog extends REST_Controller 
{
	public function __construct() {
        parent::__construct();
        $this->load->model('SpeedViolationLogModel');
    }
	
    function list_get()
    {
        $bus_id     = $this->uri->segment(3);
        $date       = $this->uri->segment(4);
        $page       = $this->uri->segment(5);
        $size       = $this->uri->segment(6);
        
        if(empty($page) || $page == '0') $page = 1;
        if(empty($size) || $size == '0') $size = 10;
        $offset = ($page-1)*$size;
        
        $bus_id	= str_replace('_', '', $bus_id);
        $date	= str_replace('_', '', $date);
        
        $crit           = array();
        $crit['bus_id'] = trim($bus_id);
        $crit['date']   = trim($date);
        
        $sort	= 'reg_date_time';
        $order	= 'desc';
        
        $rows   = $this->SpeedViolationLogModel->get_paged_list($crit, $sort, $order, $size, $offset)->result();
        $total  = $this->SpeedViolationLogModel->get_count($crit);
        $i=0;
        foreach ($rows as $row) {
            $rows[$i]->reg_date_time = date('d-m-y H:i', strtotime($row->reg_date_time));
            $i++;
        }
        $totalPage  = ceil($total/$size);
        $firstPage  = ($page == 0 || $page == 1) ? true : false;
        $lastPage   = ($page == $totalPage) ? true : false;
        $response   = array('content'=>$rows, 'totalPage'=>$totalPage, 'first'=>$firstPage, 'last'=>$lastPage, 'page'=>intval($page), 'total'=>$total, 'crit'=>$crit);
        $this->response($response, REST_Controller::HTTP_OK);
    }
    
	public function lists_get() {
		$sort	= 'reg_date_time';
		$order	= 'desc';
		$rows   = $this->SpeedViolationLogModel->get_list([], $sort, $order)->result();
		$this->response($rows, REST_Controller::HTTP_OK);
	}
	
	public function delete_get() {
		$id    = $this->uri->segment(3);
		$this->SpeedViolationLogModel->delete($id);
        $this->response([], REST_Controller::HTTP_OK);
	}
	
	public function trash_post() {
	    $values = json_decode(file_get_contents('php://input'), true);
	    $this->SpeedViolationLogModel->removes($values);
	    $this->response([], REST_Controller::HTTP_OK);
	}
}
?>