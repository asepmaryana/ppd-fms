<?php
class RoleModel extends CI_Model
{
	public $table	= 'authority';
	
	public function __construct() {
		parent::__construct();
	}
	
	public function get_count() {
		$this->db->select('count(id) as total');
		$rs = $this->db->get($this->table);        		
        if($rs->num_rows() > 0) {
            $row = $rs->row();
            return $row->total;
        } else return 0;
	}
	
	public function get_paged_list($crit, $sort, $order, $limit = 10, $offset = 0) {
	    if(isset($crit['name']) && $crit['name'] != '') $this->db->like('s.name', $crit['name']);
	    
	    if($sort != '' && $order != '') $this->db->order_by('s.'.$sort, $order);
	    return $this->db->get($this->table.' s', $limit, $offset);
	}
	
	public function get_list($crit, $sort, $order) {
	    if(isset($crit['name']) && $crit['name'] != '') $this->db->like('s.name', $crit['name']);
	    
	    if($sort != '' && $order != '') $this->db->order_by('s.'.$sort, $order);
	    return $this->db->get($this->table.' s');
	}
	
	public function get_by_id($id)
	{
		$this->db->where('id', $id);
		return $this->db->get($this->table);
	}
	
	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
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
	
}
?>