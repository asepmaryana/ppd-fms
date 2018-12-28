<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';

class User extends REST_Controller 
{
	public function __construct() {
        parent::__construct();
        $this->load->model('UsersModel');
    }
	
	public function list_get() {
		$headers	= $this->input->request_headers();
		$user		= $this->UsersModel->getInfo($headers[config_item('rest_key_name')]);
		$name		= $this->uri->segment(3);
		$page    	= $this->uri->segment(4);
        $size    	= $this->uri->segment(5);
		$doc    	= $this->uri->segment(6);
		
		$name		= str_replace('_', '', $name);
		
        if(empty($page) || $page == '0') $page = 1;
        if(empty($size) || $size == '0') $size = 9;
        $offset = ($page-1)*$size;
		
		$sort	= 'firstname';
		$order	= 'asc';
		$except	= array();
		if(!in_array($user->id, $except)) array_push($except, $user->id);
		if(empty($doc))
		{
			$rows   	= $this->UsersModel->get_paged_list($except, $name, $sort, $order, $size, $offset)->result();
			$total  	= $this->UsersModel->get_count($except, $name);
			$totalPage  = ceil($total/$size);
			$firstPage  = ($page == 0 || $page == 1) ? true : false;
			$lastPage   = ($page == $totalPage) ? true : false;
			$resp       = array('content'=>$rows, 'totalPage'=>$totalPage, 'first'=>$firstPage, 'last'=>$lastPage, 'page'=>intval($page), 'total'=>$total);
			$this->response($resp, REST_Controller::HTTP_OK);
		}
		else
		{
			
		}
	}
	
	public function photo_post() {
	    $id       = $this->uri->segment(3);
	    $values   = array();
	    if(!empty($_FILES['foto']['name'])) {
	        $folder   = 'assets/images/users/'.$id;
	        if(!is_dir('../'.$folder)) @mkdir('../'.$folder, 0755, true);
	        move_uploaded_file($_FILES['foto']['tmp_name'], '../'.$folder.'/'.$_FILES['foto']['name']);
	        $values['foto']    = $folder.'/'.$_FILES['foto']['name'];
	    }
	    $this->UsersModel->update($id, $values);
	    $user = $this->UsersModel->getInfo($headers[config_item('rest_key_name')]);
	    $values['username'] = $user->username;
	    $values['firstname'] = $user->firstname;
	    $this->response($values, REST_Controller::HTTP_OK);
	}
	
	public function save_post() {
	    $id       = $this->uuid->v4();
		$values   = json_decode($_POST['data'], true);
		$role_id  = $values['authority_id'];
		if(!empty($_FILES['foto']['name'])) {
		    $folder   = 'assets/images/users/'.$id;
		    if(!is_dir('../'.$folder)) @mkdir('../'.$folder, 0755, true);
		    move_uploaded_file($_FILES['foto']['tmp_name'], '../'.$folder.'/'.$_FILES['foto']['name']);
		    $values['foto']    = $folder.'/'.$_FILES['foto']['name'];
		}
		$values['password']   = md5($values['password']);
        $values['id']         = $id;
        
        unset($values['role']);
        unset($values['authority_id']);        
        unset($values['opassword']);
        
        if($this->UsersModel->getUser($values['username'])) $this->response(['status'=>false, 'message'=>'Username sudah terpakai !'], REST_Controller::HTTP_NOT_FOUND);
        else {
            $this->UsersModel->save($values);
            
            # insert role
            $this->rest->db->set(['users_id'=>$id,'authority_id'=>$role_id])->insert('users_authority');
            
            $this->response($values, REST_Controller::HTTP_OK);
        }
	}
	
	public function update_post() {
		$id    = $this->uri->segment(3);
		$user  = $this->UsersModel->get_by_id($id)->row();
		if($user->foto != '' && file_exists('../'.$user->foto)) @unlink('../'.$user->foto);
		
		$values   = json_decode($_POST['data'], true);
		$role_id  = $values['authority_id'];
		if(!empty($_FILES['foto']['name'])) {
		    $folder   = 'assets/images/users/'.$id;
		    if(!is_dir('../'.$folder)) @mkdir('../'.$folder, 0755, true);
		    move_uploaded_file($_FILES['foto']['tmp_name'], '../'.$folder.'/'.$_FILES['foto']['name']);
		    $values['foto']    = $folder.'/'.$_FILES['foto']['name'];
		}
		
		if($values['password'] != $values['opassword']) $values['password']   = md5($values['password']);
		else unset($values['password']);
		
		unset($values['role']);
		unset($values['authority_id']);		
		unset($values['username']);
		unset($values['opassword']);
		
		$this->UsersModel->update($id, $values);
		#update
		$this->rest->db
            ->where('users_id', $id)
            ->where('authority_id', $user->role_id)
            ->update('users_authority', ['authority_id'=>$role_id]);
		
		$this->response($values, REST_Controller::HTTP_OK);
	}
	
	public function delete_get() {
		$id    = $this->uri->segment(3);
		$this->UsersModel->delete($id);
        $this->response([], REST_Controller::HTTP_OK);
	}
	
	public function profile_post() {
		$id    = $this->uri->segment(3);
        $oldes = json_decode(file_get_contents('php://input'), true);
		$values['firstname']  = $oldes['firstname'];
		$values['lastname']	  = $oldes['lastname'];
		$values['phone']	  = $oldes['phone'];
		$this->UsersModel->update($id, $values);
		if(isset($values['username'])) $values['username'] = $oldes['username'];
		if(isset($values['foto'])) $values['foto']		= $oldes['foto'];
        $this->response($values, REST_Controller::HTTP_OK);
	}
	
	public function password_post() {
		$id    = $this->uri->segment(3);
		$user  = $this->UsersModel->get_by_id($id)->row();
		if($user){
			$oldes = json_decode(file_get_contents('php://input'), true);
			if(md5($oldes['old']) != $user->password) $this->response(['status'=>false, 'message'=>'Password lama salah.'], REST_Controller::HTTP_NOT_FOUND);
			else{
				$values['password'] = md5($oldes['password']);
				if($this->UsersModel->update($id, $values))
					$this->response(['status'=>true, 'message'=>'Password telah diubah.'], REST_Controller::HTTP_OK);
				else 
					$this->response(['status'=>false, 'message'=>'Password gagal diubah.'], REST_Controller::HTTP_OK);
			}
		}
		else $this->response(['status'=>false, 'message'=>'User tidak ditemukan.'], REST_Controller::HTTP_NOT_FOUND);
	}
}
?>