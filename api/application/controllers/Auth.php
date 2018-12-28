<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';
require_once APPPATH . 'third_party/phpmailer/PHPMailerAutoload.php';

class Auth extends REST_Controller 
{

	public function __construct() {
        parent::__construct();
        $this->load->model('UsersModel');
		$this->load->model('KeysModel');
		$this->load->model('ConfigEmailModel');
		$this->load->library('uuid');
    }
	
	public function index_get() {
		$this->set_response(array('success'=>true, 'version'=>phpversion()), REST_Controller::HTTP_OK);
	}
	
	public function login_post() {
		$username = $this->post('username');
		$password = md5($this->post('password'));
		#$password = $this->post('password');
		
		if($username == '') {
            $postdata   = file_get_contents("php://input");
            $request    = json_decode($postdata);
            $username   = $request->username;
            $password   = md5($request->password);
            #$password   = $request->password;
        }
		
        $response = ['success'=>false, 'message' => 'Username atau password anda salah !'];
        
        if(!$username || !$password) $this->response($response, REST_Controller::HTTP_NOT_FOUND);
        else
        {
            $user = $this->UsersModel->login($username, $password);
            #print_r($user);
            if($user) {
                $output['success']	= true;
    			$output['token']	= $this->uuid->v4(true);
                $output['created'] 	= time();
                $output['expired'] 	= time() + 60*60*8;
                $output['users_id']     = $user->users_id;
                $output['authority_id'] = $user->authority_id;
                
                #if($user->authority_id == 1 || $user->authority_id == 2) {
                    #insert into keys table
                    $this->rest->db
                    ->insert(
                        $this->config->item('rest_keys_table'), [
                            'user_id' => $user->users_id,
                            'key' => $output['token'],
                            'level' => $user->authority_id,
                            'ip_addresses' => $this->input->ip_address(),
                            'date_created' => time()
                        ]);
                    $this->set_response($output, REST_Controller::HTTP_OK);
                #}
                #else $this->set_response($response = ['success'=>false, 'message' => 'Tidak Diperbolehkan Masuk !'], REST_Controller::HTTP_NOT_FOUND);
            }
            else {
                $this->set_response($response, REST_Controller::HTTP_NOT_FOUND);
            }
        }
	}
    
	public function logout_get() {
		$headers	= $this->input->request_headers();
		if(isset($headers[config_item('rest_key_name')])) {
            $this->rest->db
                ->where(config_item('rest_key_column'), $headers[config_item('rest_key_name')])
                ->delete(config_item('rest_keys_table'));
			$this->set_response(['success'=>true, 'message'=>'Logout berhasil'], REST_Controller::HTTP_OK);
		}
		else $this->set_response(['success'=>false, 'message'=>'Logout gagal !'], REST_Controller::HTTP_NOT_FOUND);		
	}
	
	public function signup_post() {
	    $request   = json_decode (file_get_contents("php://input"), true);
	    if($this->UsersModel->getUser(trim($request['username']))) 
	        $this->set_response(['success'=>false, 'message'=>'Username sudah terdaftar.'], REST_Controller::HTTP_NOT_FOUND);
        else {
            $id         = $this->uuid->v4();            
            #akun
            $user                   = array();
            $user['id']             = $id;            
            $user['username']       = $request['username'];
            $user['password']       = md5($request['password']);
            $user['firstname']      = $request['firstname'];
            if(isset($request['lastname'])) $user['lastname']       = $request['lastname'];
            $this->rest->db->set($user)->insert('users');
            
            #role
            $this->rest->db->set(['users_id'=>$id, 'authority_id'=>'1'])->insert('users_authority');
            
            $this->set_response(['success'=>true, 'message'=>'Registrasi berhasil.'], REST_Controller::HTTP_OK);
        }
	}
	
	public function reset_post() {
	    $request   = json_decode (file_get_contents("php://input"), true);
	    $user = $this->UsersModel->getUser(trim($request['username']));
	    if($user) {
	        $newPassword   = $this->random_password(10);
	        // get config email
	        $cfg   = $this->ConfigEmailModel->fetch()->row();
	        //Create a new PHPMailer instance
	        $mail = new PHPMailer();
	        //Tell PHPMailer to use SMTP
	        $mail->isSMTP();
	        //Enable SMTP debugging
	        // 0 = off (for production use)
	        // 1 = client messages
	        // 2 = client and server messages
	        $mail->SMTPDebug = 0;
	        //Ask for HTML-friendly debug output
	        $mail->Debugoutput = 'html';
	        //Set the hostname of the mail server
	        $mail->Host = $cfg->smtp_host;
	        //Set the SMTP port number - likely to be 25, 465 or 587
	        $mail->Port = $cfg->smtp_port;
	        //Whether to use SMTP authentication
	        $mail->SMTPAuth = true;
	        //Username to use for SMTP authentication
	        $mail->Username = $cfg->smtp_user;
	        //Password to use for SMTP authentication
	        $mail->Password = $cfg->smtp_pass;
	        //Set who the message is to be sent from
	        $mail->setFrom($cfg->smtp_user, $cfg->smtp_account);
	        //Set an alternative reply-to address
	        //$mail->addReplyTo('replyto@example.com', 'First Last');
	        //Set who the message is to be sent to
	        $mail->addAddress($request['username'], $user->firstname);
	        //Set the subject line
	        $mail->Subject = 'Reset Password';
	        //Read an HTML message body from an external file, convert referenced images to embedded,
	        //convert HTML into a basic plain-text alternative body
	        $mail->msgHTML('New Password : <strong>'.$newPassword.'</strong>');
	        //Replace the plain text body with one created manually
	        //$mail->AltBody = 'New Password : '.$this->random_password(10).'';
	        //Attach an image file
	        //$mail->addAttachment('images/phpmailer_mini.png');
	        
	        //send the message, check for errors
	        if (!$mail->send()) {
	            $this->set_response(['success'=>false, 'message'=>'Reset password gagal.'], REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
	        } else {
	            $this->rest->db->where('id', $user->id)->update('users', ['password'=>enkripsi($newPassword)]);
	            $this->set_response(['success'=>true, 'message'=>'Reset berhasil. Silahkan periksa kotak inbox email anda untuk melihat password yang baru.'], REST_Controller::HTTP_OK);
	        }
	    }
	    else $this->set_response(['success'=>false, 'message'=>'Username belum terdaftar.'], REST_Controller::HTTP_NOT_FOUND);
	}
	
	public function info_get() {
        $headers	= $this->input->request_headers();
        if(isset($headers[config_item('rest_key_name')])) {
            $info   = $this->rest->db
                ->select('u.*,ua.authority_id,a.name as role')
                ->join('users_authority ua', 'ua.users_id=u.id')
                ->join('authority a', 'ua.authority_id=a.id')
                ->join('keys k', 'k.user_id=u.id', 'left')
                ->where('k.key', $headers[config_item('rest_key_name')])
                ->get('users u')
                ->row();
            $this->set_response($info, REST_Controller::HTTP_OK);
        }
        else $this->set_response([], REST_Controller::HTTP_NOT_FOUND);
	}
	
	private function random_password($length) {
	    return substr(sha1(rand()), 0, $length);
	}
	
	public function find_get() {
	    $role_id	= $this->uri->segment(3);
	    $info   = $this->rest->db->select('*')->where('id', $role_id)->get('authority')->row();
	    $this->set_response($info, REST_Controller::HTTP_OK);
	}
}
?>