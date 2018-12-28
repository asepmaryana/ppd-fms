<?php
class RouteDeviationLogModel extends CI_Model
{
	public $table	= 'route_deviation_log';
	
	public function __construct() {
		parent::__construct();
	}
	
	public function get_count($crit) {
	    $this->db->select('count(rdl.id) as total');
	    if(isset($crit['bus_id']) && $crit['bus_id'] != '') $this->db->where('rdl.bus_id', $crit['bus_id']);
	    if(isset($crit['date']) && $crit['date'] != '') $this->db->where('DATE(rdl.reg_date_time)', $crit['date']);
	    $rs = $this->db->get($this->table.' rdl');
	    if($rs->num_rows() > 0) {
	        $row = $rs->row();
	        return $row->total;
	    } else return 0;
	}
	
	public function get_paged_list($crit, $sort, $order, $limit = 10, $offset = 0) {
	    $this->db->select('rdl.*,b.car_number,b.name');
	    $this->db->join('bus b', 'rdl.bus_id=b.id', 'left');
	    if(isset($crit['bus_id']) && $crit['bus_id'] != '') $this->db->where('rdl.bus_id', $crit['bus_id']);
	    if(isset($crit['date']) && $crit['date'] != '') $this->db->where('DATE(rdl.reg_date_time)', $crit['date']);
	    if($sort != '' && $order != '') $this->db->order_by('rdl.'.$sort, $order);
	    return $this->db->get($this->table.' rdl', $limit, $offset);
	}
	
	public function get_list($crit, $sort, $order) {
	    $this->db->select('rdl.*,b.car_number,b.name');
	    $this->db->join('bus b', 'rdl.bus_id=b.id', 'left');
	    if(isset($crit['bus_id']) && $crit['bus_id'] != '') $this->db->where('rdl.bus_id', $crit['bus_id']);
	    if(isset($crit['date']) && $crit['date'] != '') $this->db->where('DATE(rdl.reg_date_time)', $crit['date']);
	    if($sort != '' && $order != '') $this->db->order_by('rdl.'.$sort, $order);
	    return $this->db->get($this->table.' rdl');
	}
	
	public function get_by_id($id)
	{
	    $this->db->select('rdl.*');
		$this->db->where('rdl.id', $id);
		return $this->db->get($this->table.' rdl');
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
	
	public function get_count_bus(){
	    $this->db->select('b.name as label,count(rdl.id) as data');
	    $this->db->join('bus b', 'rdl.bus_id=b.id', 'left');
	    $this->db->group_by('b.name');
	    $this->db->having('count(rdl.id) > 0');
	    $this->db->order_by('b.name');
	    return $this->db->get($this->table.' rdl');
	}
}
?>