<?php
class SpeedViolationLogModel extends CI_Model
{
	public $table	= 'speed_violation_log';
	
	public function __construct() {
		parent::__construct();
	}
	
	public function get_count($crit) {
		$this->db->select('count(id) as total');
		if(isset($crit['bus_id']) && $crit['bus_id'] != '') $this->db->where('bus_id', $crit['bus_id']);
		if(isset($crit['date']) && $crit['date'] != '') $this->db->where('DATE(reg_date_time)', $crit['date']);
		$rs = $this->db->get($this->table);        		
        if($rs->num_rows() > 0) {
            $row = $rs->row();
            return $row->total;
        } else return 0;
	}
	
	public function get_paged_list($crit, $sort, $order, $limit = 10, $offset = 0) {
	    $this->db->select('svl.*,b.car_number,b.name');
	    $this->db->join('bus b', 'svl.bus_id=b.id', 'left');
	    if(isset($crit['bus_id']) && $crit['bus_id'] != '') $this->db->where('svl.bus_id', $crit['bus_id']);
	    if(isset($crit['date']) && $crit['date'] != '') $this->db->where('DATE(svl.reg_date_time)', $crit['date']);
		if($sort != '' && $order != '') $this->db->order_by('svl.'.$sort, $order);
        return $this->db->get($this->table.' svl', $limit, $offset);
	}
	
	public function get_list($crit, $sort, $order) {
	    $this->db->select('svl.*,b.car_number,b.name');
	    $this->db->join('bus b', 'svl.bus_id=b.id', 'left');
	    if(isset($crit['bus_id']) && $crit['bus_id'] != '') $this->db->where('svl.bus_id', $crit['bus_id']);
	    if(isset($crit['date']) && $crit['date'] != '') $this->db->where('DATE(svl.reg_date_time)', $crit['date']);
	    if($sort != '' && $order != '') $this->db->order_by('svl.'.$sort, $order);
        return $this->db->get($this->table.' svl');
	}
	
	public function get_by_id($id)
	{
	    $this->db->select('svl.*');
		$this->db->where('svl.id', $id);
		return $this->db->get($this->table.' svl');
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
	
	public function removes($ids)
	{
	    $this->db->where_in('id', $ids);
	    return $this->db->delete($this->table);
	}
}
?>