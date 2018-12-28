<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';

class ManCompany extends REST_Controller 
{
	public function __construct() {
        parent::__construct();
        $this->load->model('ManCompanyModel');
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
        
        $rows   = $this->ManCompanyModel->get_paged_list($crit, $sort, $order, $size, $offset)->result();
        $total  = $this->ManCompanyModel->get_count($crit);
        $totalPage  = ceil($total/$size);
        $firstPage  = ($page == 0 || $page == 1) ? true : false;
        $lastPage   = ($page == $totalPage) ? true : false;
        $response   = array('content'=>$rows, 'totalPage'=>$totalPage, 'first'=>$firstPage, 'last'=>$lastPage, 'page'=>intval($page), 'total'=>$total, 'crit'=>$crit);
        $this->response($response, REST_Controller::HTTP_OK);
    }
    
	public function lists_get() {
		$sort	= 'code';
		$order	= 'asc';
		$rows   = $this->ManCompanyModel->get_list([], $sort, $order)->result();
		$this->response($rows, REST_Controller::HTTP_OK);
	}
	
	public function save_post() {
		$values = json_decode(file_get_contents('php://input'), true);
		$values['code']   = $this->ManCompanyModel->get_auto();
		$values['id'] = $this->ManCompanyModel->save($values);
        $this->response($values, REST_Controller::HTTP_OK);
	}
	
	public function update_post() {
		$id    = $this->uri->segment(3);
        $values = json_decode(file_get_contents('php://input'), true);
        $this->ManCompanyModel->update($id, $values);
        $this->response($values, REST_Controller::HTTP_OK);
	}
	
	public function delete_get() {
		$id    = $this->uri->segment(3);
		$this->ManCompanyModel->delete($id);
        $this->response([], REST_Controller::HTTP_OK);
	}
	
	public function trash_post() {
	    $values = json_decode(file_get_contents('php://input'), true);
	    $this->ManCompanyModel->removes($values);
	    $this->response([], REST_Controller::HTTP_OK);
	}
}
?>