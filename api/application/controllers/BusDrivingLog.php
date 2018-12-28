<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';

class BusDrivingLog extends REST_Controller 
{
	public function __construct() {
        parent::__construct();
        $this->load->model('BusDrivingLogModel');
    }
	
    function list_get()
    {
        $bus_id     = $this->uri->segment(3);
        $sdate      = $this->uri->segment(4);
        $edate      = $this->uri->segment(5);
        $page       = $this->uri->segment(6);
        $size       = $this->uri->segment(7);
        $doc        = $this->uri->segment(8);
        
        if(empty($page) || $page == '0') $page = 1;
        if(empty($size) || $size == '0') $size = 10;
        $offset = ($page-1)*$size;
        
        $bus_id	= str_replace('_', '', $bus_id);
        $sdate	= str_replace('_', '', $sdate);
        $edate	= str_replace('_', '', $edate);
        
        $crit           = array();
        $crit['bus_id'] = trim($bus_id);
        $crit['sdate']  = ($sdate == '') ? date('Y-m-d') : $sdate;
        $crit['edate']  = ($edate == '') ? date('Y-m-d') : $edate;
        
        $sort	= 'reg_date_time';
        $order	= 'asc';
        
        if($doc == '') {
            $rows   = $this->BusDrivingLogModel->get_paged_list($crit, $sort, $order, $size, $offset)->result();
            $total  = $this->BusDrivingLogModel->get_count($crit);
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
        else {
            $bus    = $this->BusModel->get_by_id($bus_id)->row();
            $rows   = $this->BusDrivingLogModel->get_list($crit, $sort, $order)->result();
            $i=0;
            foreach ($rows as $row) {
                $rows[$i]->reg_date_time = date('d-m-y H:i', strtotime($row->reg_date_time));
                $i++;
            }
            
        }
    }
    
	public function lists_get() {
	    $bus_id     = $this->uri->segment(3);
	    $sdate      = $this->uri->segment(4);
	    $edate      = $this->uri->segment(5);
	    $page       = $this->uri->segment(6);
	    $size       = $this->uri->segment(7);
	    $doc        = $this->uri->segment(8);
	    
	    $bus_id	= str_replace('_', '', $bus_id);
	    $sdate	= str_replace('_', '', $sdate);
	    $edate	= str_replace('_', '', $edate);
	    
	    $crit           = array();
	    $crit['bus_id'] = trim($bus_id);
	    $crit['sdate']  = ($sdate == '') ? date('Y-m-d') : $sdate;
	    $crit['edate']  = ($edate == '') ? date('Y-m-d') : $edate;
	    
	    $sort	= 'reg_date_time';
	    $order	= 'asc';
		
	    $rows   = $this->BusDrivingLogModel->get_list($crit, $sort, $order)->result();
		$this->response($rows, REST_Controller::HTTP_OK);
	}
	
	public function delete_get() {
		$id    = $this->uri->segment(3);
		$this->BusDrivingLogModel->delete($id);
        $this->response([], REST_Controller::HTTP_OK);
	}
	
	public function trash_post() {
	    $values = json_decode(file_get_contents('php://input'), true);
	    $this->BusDrivingLogModel->removes($values);
	    $this->response([], REST_Controller::HTTP_OK);
	}
}
?>