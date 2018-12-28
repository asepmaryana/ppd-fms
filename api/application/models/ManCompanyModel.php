<?php
class ManCompanyModel extends CI_Model
{
	public $table	= 'manufacture_company';
	
	public function __construct() {
		parent::__construct();
	}
	
	public function get_count($crit) {
	    $this->db->select('count(s.id) as total');
	    if(isset($crit['code']) && $crit['code'] != '') $this->db->like('lower(s.code)', strtolower(trim($crit['code'])));
	    if(isset($crit['name']) && $crit['name'] != '') $this->db->like('s.name', $crit['name']);
	    
	    $rs = $this->db->get($this->table.' s');
	    if($rs->num_rows() > 0) {
	        $row = $rs->row();
	        return $row->total;
	    } else return 0;
	}
	
	public function get_paged_list($crit, $sort, $order, $limit = 10, $offset = 0) {
	    $this->db->select('s.*');
	    if(isset($crit['code']) && $crit['code'] != '') $this->db->like('lower(s.code)', strtolower(trim($crit['code'])));
	    if(isset($crit['name']) && $crit['name'] != '') $this->db->like('s.name', $crit['name']);
	    
	    if($sort != '' && $order != '') $this->db->order_by('s.'.$sort, $order);
	    return $this->db->get($this->table.' s', $limit, $offset);
	}
	
	public function get_list($crit, $sort, $order) {
	    $this->db->select('s.*');
	    if(isset($crit['code']) && $crit['code'] != '') $this->db->like('lower(s.code)', strtolower(trim($crit['code'])));
	    if(isset($crit['name']) && $crit['name'] != '') $this->db->like('s.name', $crit['name']);
	    
	    if($sort != '' && $order != '') $this->db->order_by('s.'.$sort, $order);
	    return $this->db->get($this->table.' s');
	}
	
	public function get_by_id($id)
	{
	    $this->db->select('s.*');
		$this->db->where('s.id', $id);
		return $this->db->get($this->table.' s');
	}
	
	public function get_by_code($code)
	{
	    $this->db->where('code', trim($code));
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
		return $this->db->update($this->table, $data);
	}
	
	public function delete($id)
	{
		$this->db->where('id', $id);
		return $this->db->delete($this->table);
	}
	
	public function removes($ids)
	{
	    $this->db->where_in('id', $ids);
	    return $this->db->delete($this->table);
	}
	
	public function get_auto() {
	    $this->db->select('code');
	    $this->db->order_by('code', 'desc');
	    $rs = $this->db->get($this->table, 1, 0);
	    if($rs->num_rows() > 0) {
	        $row = $rs->row();
	        $last= $row->code;
	        $new    = str_replace('.', '', $last);
	        $val    = intval($new)+1;
	        $str    = "$val";
	        return $str;
	    } else return '10001';
	}
}
?>