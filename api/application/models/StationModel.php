<?php
class StationModel extends CI_Model
{
	public $table	= 'station';
	
	public function __construct() {
		parent::__construct();
	}
	
	public function get_count($crit) {
	    $this->db->select('count(s.id) as total');
	    if(isset($crit['code']) && $crit['code'] != '') $this->db->like('lower(s.code)', strtolower(trim($crit['code'])));
	    if(isset($crit['name']) && $crit['name'] != '') $this->db->like('s.name', $crit['name']);
	    if(isset($crit['route_line_id']) && $crit['route_line_id'] != '') $this->db->where('s.route_line_id', $crit['route_line_id']);
	    if(isset($crit['station_direct_id']) && $crit['station_direct_id'] != '') $this->db->where('s.station_direct_id', $crit['station_direct_id']);
	    if(isset($crit['station_type_id']) && $crit['station_type_id'] != '') $this->db->where('s.station_type_id', $crit['station_type_id']);
	    if(isset($crit['service_group_id']) && $crit['service_group_id'] != '') $this->db->where('s.service_group_id', $crit['service_group_id']);
	    if(isset($crit['service_status_id']) && $crit['service_status_id'] != '') $this->db->where('s.service_status_id', $crit['service_status_id']);
	    $rs = $this->db->get($this->table.' s');
	    if($rs->num_rows() > 0) {
	        $row = $rs->row();
	        return $row->total;
	    } else return 0;
	}
	
	public function get_paged_list($crit, $sort, $order, $limit = 10, $offset = 0) {
	    $this->db->select('s.*,rl.name as route_line,sd.name as station_direct,st.name as station_type,sg.name as service_group,ss.name as service_status');
	    
	    $this->db->join('route_line rl', 's.route_line_id=rl.id', 'left');
	    $this->db->join('station_direct sd', 's.station_direct_id=sd.id', 'left');
	    $this->db->join('station_type st', 's.station_type_id=st.id', 'left');
	    $this->db->join('service_group sg', 's.service_group_id=sg.id', 'left');
	    $this->db->join('service_status ss', 's.service_status_id=ss.id', 'left');
	    
	    if(isset($crit['code']) && $crit['code'] != '') $this->db->like('lower(s.code)', strtolower(trim($crit['code'])));
	    if(isset($crit['name']) && $crit['name'] != '') $this->db->like('s.name', $crit['name']);
	    if(isset($crit['route_line_id']) && $crit['route_line_id'] != '') $this->db->where('s.route_line_id', $crit['route_line_id']);
	    if(isset($crit['station_direct_id']) && $crit['station_direct_id'] != '') $this->db->where('s.station_direct_id', $crit['station_direct_id']);
	    if(isset($crit['station_type_id']) && $crit['station_type_id'] != '') $this->db->where('s.station_type_id', $crit['station_type_id']);
	    if(isset($crit['service_group_id']) && $crit['service_group_id'] != '') $this->db->where('s.service_group_id', $crit['service_group_id']);
	    if(isset($crit['service_status_id']) && $crit['service_status_id'] != '') $this->db->where('s.service_status_id', $crit['service_status_id']);
	    if($sort != '' && $order != '') $this->db->order_by('s.'.$sort, $order);
	    return $this->db->get($this->table.' s', $limit, $offset);
	}
	
	public function get_list($crit, $sort, $order) {
	    $this->db->select('s.*,rl.name as route_line,sd.name as station_direct,st.name as station_type,sg.name as service_group,ss.name as service_status');
	    
	    $this->db->join('route_line rl', 's.route_line_id=rl.id', 'left');
	    $this->db->join('station_direct sd', 's.station_direct_id=sd.id', 'left');
	    $this->db->join('station_type st', 's.station_type_id=st.id', 'left');
	    $this->db->join('service_group sg', 's.service_group_id=sg.id', 'left');
	    $this->db->join('service_status ss', 's.service_status_id=ss.id', 'left');
	    
	    if(isset($crit['code']) && $crit['code'] != '') $this->db->like('lower(s.code)', strtolower(trim($crit['code'])));
	    if(isset($crit['name']) && $crit['name'] != '') $this->db->like('s.name', $crit['name']);
	    if(isset($crit['route_line_id']) && $crit['route_line_id'] != '') $this->db->where('s.route_line_id', $crit['route_line_id']);
	    if(isset($crit['station_direct_id']) && $crit['station_direct_id'] != '') $this->db->where('s.station_direct_id', $crit['station_direct_id']);
	    if(isset($crit['station_type_id']) && $crit['station_type_id'] != '') $this->db->where('s.station_type_id', $crit['station_type_id']);
	    if(isset($crit['service_group_id']) && $crit['service_group_id'] != '') $this->db->where('s.service_group_id', $crit['service_group_id']);
	    if(isset($crit['service_status_id']) && $crit['service_status_id'] != '') $this->db->where('s.service_status_id', $crit['service_status_id']);
	    if($sort != '' && $order != '') $this->db->order_by('s.'.$sort, $order);
	    return $this->db->get($this->table.' s');
	}
	
	public function get_by_id($id)
	{
	    $this->db->select('s.*,rl.name as route_line,sd.name as station_direct,st.name as station_type,sg.name as service_group,ss.name as service_status');
	    $this->db->join('route_line rl', 's.route_line_id=rl.id', 'left');
	    $this->db->join('station_direct sd', 's.station_direct_id=sd.id', 'left');
	    $this->db->join('station_type st', 's.station_type_id=st.id', 'left');
	    $this->db->join('service_group sg', 's.service_group_id=sg.id', 'left');
	    $this->db->join('service_status ss', 's.service_status_id=ss.id', 'left');
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
	        $new    = str_replace('SID-', '', $last);
	        $val    = intval($new)+1;
	        $str    = "$val";
	        $str    = "SID-".str_repeat('0', 4-strlen($str)).$str;
	        return $str;
	    } else return 'SID-0001';
	}
}
?>