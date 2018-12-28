<?php
class RouteMapModel extends CI_Model
{
	public $table	= 'route_map';
	
	public function __construct() {
		parent::__construct();
	}
	
	public function get_count($crit) {
	    $this->db->select('count(rm.id) as total');
	    if(isset($crit['map_name']) && $crit['map_name'] != '') $this->db->like('lower(rm.map_name)', strtolower(trim($crit['map_name'])));
	    if(isset($crit['service_group_id']) && $crit['service_group_id'] != '') $this->db->where('rm.service_group_id', $crit['service_group_id']);
	    if(isset($crit['service_status_id']) && $crit['service_status_id'] != '') $this->db->where('rm.service_status_id', $crit['service_status_id']);
	    
	    $rs = $this->db->get($this->table.' rm');
	    if($rs->num_rows() > 0) {
	        $row = $rs->row();
	        return $row->total;
	    } else return 0;
	}
	
	public function get_paged_list($crit, $sort, $order, $limit = 10, $offset = 0) {
	    $this->db->select('rm.*,sg.name as service_group,ss.name as service_status');
	    $this->db->join('service_group sg', 'rm.service_group_id=sg.id', 'left');
	    $this->db->join('service_status ss', 'rm.service_status_id=ss.id', 'left');
	    if(isset($crit['map_name']) && $crit['map_name'] != '') $this->db->like('lower(rm.map_name)', strtolower(trim($crit['map_name'])));
	    if(isset($crit['service_group_id']) && $crit['service_group_id'] != '') $this->db->where('rm.service_group_id', $crit['service_group_id']);
	    if(isset($crit['service_status_id']) && $crit['service_status_id'] != '') $this->db->where('rm.service_status_id', $crit['service_status_id']);
	    
	    if($sort != '' && $order != '') $this->db->order_by('rm.'.$sort, $order);
	    return $this->db->get($this->table.' rm', $limit, $offset);
	}
	
	public function get_list($crit, $sort, $order) {
	    $this->db->select('rm.*,sg.name as service_group,ss.name as service_status');
	    $this->db->join('service_group sg', 'rm.service_group_id=sg.id', 'left');
	    $this->db->join('service_status ss', 'rm.service_status_id=ss.id', 'left');
	    if(isset($crit['map_name']) && $crit['map_name'] != '') $this->db->like('lower(rm.map_name)', strtolower(trim($crit['map_name'])));
	    if(isset($crit['service_group_id']) && $crit['service_group_id'] != '') $this->db->where('rm.service_group_id', $crit['service_group_id']);
	    if(isset($crit['service_status_id']) && $crit['service_status_id'] != '') $this->db->where('rm.service_status_id', $crit['service_status_id']);
	    
	    if($sort != '' && $order != '') $this->db->order_by('rm.'.$sort, $order);
	    return $this->db->get($this->table.' rm');
	}
	
	public function get_by_id($id)
	{
	    $this->db->select('rm.*');
		$this->db->where('rm.id', $id);
		return $this->db->get($this->table.' rm');
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
}
?>