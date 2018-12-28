<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';

class Line extends REST_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('LineModel');
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
        
        $sort	= 'name';
        $order	= 'asc';
        
        $rows   = $this->LineModel->get_paged_list($crit, $sort, $order, $size, $offset)->result();
        $total  = $this->LineModel->get_count($crit);
        $totalPage  = ceil($total/$size);
        $firstPage  = ($page == 0 || $page == 1) ? true : false;
        $lastPage   = ($page == $totalPage) ? true : false;
        $response   = array('content'=>$rows, 'totalPage'=>$totalPage, 'first'=>$firstPage, 'last'=>$lastPage, 'page'=>intval($page), 'total'=>$total, 'crit'=>$crit);
        $this->response($response, REST_Controller::HTTP_OK);
    }
    
    public function lists_get() {
        $sort	= 'id';
        $order	= 'asc';
        $rows   = $this->LineModel->get_list([], $sort, $order)->result();
        $this->response($rows, REST_Controller::HTTP_OK);
    }
    
    public function save_post() {
        $values = json_decode(file_get_contents('php://input'), true);
        if(isset($values['lati_long'])) $values['lati_long'] = json_encode($values['lati_long']);
        $values['id'] = $this->LineModel->save($values);
        $this->response($values, REST_Controller::HTTP_OK);
    }
    
    public function update_post() {
        $id    = $this->uri->segment(3);
        $values = json_decode(file_get_contents('php://input'), true);
        if(isset($values['lati_long'])) $values['lati_long'] = json_encode($values['lati_long']);
        $this->LineModel->update($id, $values);
        $this->response($values, REST_Controller::HTTP_OK);
    }
    
    public function delete_get() {
        $id    = $this->uri->segment(3);
        $this->LineModel->delete($id);
        $this->response([], REST_Controller::HTTP_OK);
    }
    
    public function trash_post() {
        $values = json_decode(file_get_contents('php://input'), true);
        $this->LineModel->removes($values);
        $this->response([], REST_Controller::HTTP_OK);
    }
}
?>