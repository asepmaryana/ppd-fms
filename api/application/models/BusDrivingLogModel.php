<?php
class BusDrivingLogModel extends CI_Model
{
	public $table	= 'bus_driving_log';
	
	public function __construct() {
		parent::__construct();
	}
	
	public function get_count($crit) {
		$this->db->select('count(id) as total');
		if(isset($crit['bus_id']) && $crit['bus_id'] != '') $this->db->where('bus_id', $crit['bus_id']);
		if(isset($crit['sdate']) && isset($crit['sdate'])) {
		    if($crit['sdate'] == $crit['edate']) $this->db->where('DATE(reg_date_time)', $crit['sdate']);
		    else $this->db->where("reg_date_time between '".$crit['sdate']." 00:00:00' and '".$crit['edate']." 23:59:59'");
		}
		$rs = $this->db->get($this->table);        		
        if($rs->num_rows() > 0) {
            $row = $rs->row();
            return $row->total;
        } else return 0;
	}
	
	public function get_paged_list($crit, $sort, $order, $limit = 10, $offset = 0) {
	    $this->db->select('bdl.*');
	    $this->db->join('bus b', 'bdl.bus_id=b.id', 'left');
	    if(isset($crit['bus_id']) && $crit['bus_id'] != '') $this->db->where('bdl.bus_id', $crit['bus_id']);
	    if(isset($crit['sdate']) && isset($crit['sdate'])) {
	        if($crit['sdate'] == $crit['edate']) $this->db->where('DATE(bdl.reg_date_time)', $crit['sdate']);
	        else $this->db->where("bdl.reg_date_time between '".$crit['sdate']." 00:00:00' and '".$crit['edate']." 23:59:59'");
	    }
		if($sort != '' && $order != '') $this->db->order_by('bdl.'.$sort, $order);
        return $this->db->get($this->table.' bdl', $limit, $offset);
	}
	
	public function get_list($crit, $sort, $order) {
	    $this->db->select('bdl.*');
	    $this->db->join('bus b', 'bdl.bus_id=b.id', 'left');
	    if(isset($crit['bus_id']) && $crit['bus_id'] != '') $this->db->where('bdl.bus_id', $crit['bus_id']);
	    if(isset($crit['sdate']) && isset($crit['sdate'])) {
	        if($crit['sdate'] == $crit['edate']) $this->db->where('DATE(bdl.reg_date_time)', $crit['sdate']);
	        else $this->db->where("bdl.reg_date_time between '".$crit['sdate']." 00:00:00' and '".$crit['edate']." 23:59:59'");
	    }
	    if($sort != '' && $order != '') $this->db->order_by('bdl.'.$sort, $order);
        return $this->db->get($this->table.' bdl');
	}
	
	public function get_by_id($id)
	{
	    $this->db->select('bdl.*');
		$this->db->where('bdl.id', $id);
		return $this->db->get($this->table.' bdl');
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