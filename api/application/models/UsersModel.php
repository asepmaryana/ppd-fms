<?php
class UsersModel extends CI_Model
{
	public $table	= 'users';
	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function login($username, $password)
	{
		$this->db->select('ua.users_id,ua.authority_id');
		$this->db->from($this->table.' u');
		$this->db->join('users_authority ua', 'u.id=ua.users_id', 'left');
		$this->db->where('u.username', $username);
		$this->db->where('u.password', $password);
		$rs = $this->db->get();
		if ($rs->num_rows() > 0) return $rs->row();
		return false;
	}
    
    public function getInfo($key)
    {
        $this->db->select('u.*');
        $this->db->from($this->table.' u');
        $this->db->join('keys k', 'k.user_id=u.id', 'left');
		$this->db->where('k.key', $key);
		$rs = $this->db->get();
        return $rs->row();
    }
    
    public function getUser($username)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where('username', $username);
		$rs = $this->db->get();
        if ($rs->num_rows() > 0) return $rs->row();
		return false;
    }
    
    public function getUserByToken($token)
    {
        $this->db->select('u.*');
        $this->db->from($this->table);
        $this->db->join('users_authority ua', 'ua.users_id=u.id');
        #$this->db->join('acc_keys k', 'k.user_id=u.id_pengguna', 'left');
        $this->db->where('k.key', $token);
        $rs = $this->db->get();
        if ($rs->num_rows() > 0) return $rs->row();
        return false;
    }
    
    public function save($user)
    {
        return $this->db->insert($this->table, $user);
    }
	
	public function get_by_id($id)
	{
		$this->db->select('u.*,a.name as role');
		$this->db->join('users_authority ua', 'u.id=ua.users_id', 'left');
		$this->db->join('authority a', 'ua.authority_id=a.id', 'left');
		$this->db->where('u.id', $id);
		return $this->db->get($this->table.' u');
	}
	
	public function get_by_role($role_id)
	{
	    $this->db->select('u.id');
	    $this->db->join('users_authority ua', 'u.id=ua.users_id', 'left');
		if(is_array($role_id) && count($role_id) > 0) $this->db->where_in('ua.authority_id', $role_id);
	    if(!is_array($role_id) && $role_id != '') $this->db->where('ua.authority_id', $role_id);
	    return $this->db->get($this->table.' u');
	}
	
	public function update($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update($this->table, $data);
	}
	
	public function delete($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->table);
	}
	
	public function get_count($except, $name)
	{
		$this->db->select('count(u.id) as total');
		$this->db->join('users_authority ua', 'u.id=ua.users_id', 'left');
		if(is_array($except) && count($except)>0) $this->db->where_not_in('u.id', $except);
		if(!is_array($except) && $except != '') $this->db->where('u.id !='.$except);
		if($name != '') $this->db->like('lower(u.firstname)', $name);
		
		$rs = $this->db->get($this->table.' u');        		
        if($rs->num_rows() > 0) {
            $row = $rs->row();
            return $row->total;
        } else return 0;
	}
	
	public function get_paged_list($except, $name, $sort, $order, $limit = 10, $offset = 0)
	{
		$this->db->select('u.*,a.name as role,ua.authority_id');
		$this->db->join('users_authority ua', 'u.id=ua.users_id', 'left');
		$this->db->join('authority a', 'ua.authority_id=a.id', 'left');
		if(is_array($except) && count($except)>0) $this->db->where_not_in('u.id', $except);
		if(!is_array($except) && $except != '') $this->db->where('u.id !='.$except);
		if($name != '') $this->db->like('lower(u.firstname)', $name);
		
		if($sort != '' && $order != '') $this->db->order_by('u.'.$sort, $order);
        return $this->db->get($this->table.' u', $limit, $offset);
	}	
	
}
?>