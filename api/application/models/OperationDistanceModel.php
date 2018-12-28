<?php
class OperationDistanceModel extends CI_Model
{
	public $table	= 'operation_distance';
	
	public function __construct() {
		parent::__construct();
	}
	
	public function get_count_bus($crit){
	    $this->db->select('b.name,sum(od.distance) as total');
	    $this->db->join('bus b', 'od.bus_id=b.id', 'left');
	    if(isset($crit['bus_id']) && $crit['bus_id'] != '') $this->db->where('od.bus_id', $crit['bus_id']);
	    if(isset($crit['reg_date']) && $crit['reg_date'] != '') $this->db->where('od.reg_date', $crit['reg_date']);
	    $this->db->group_by('b.name');
	    #$this->db->having('sum(od.distance) > 0');
	    $this->db->order_by('b.name');
	    return $this->db->get($this->table.' od');
	}
}
?>