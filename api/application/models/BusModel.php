<?php
class BusModel extends CI_Model
{
	public $table	= 'bus';
	
	public function __construct() {
		parent::__construct();
	}
	
	public function get_count($crit) {
	    $this->db->select('count(s.id) as total');
	    
	    if(isset($crit['code']) && $crit['code'] != '') $this->db->like('lower(s.code)', strtolower(trim($crit['code'])));
	    if(isset($crit['name']) && $crit['name'] != '') $this->db->like('lower(s.name)', strtolower(trim($crit['name'])));
	    if(isset($crit['car_number']) && $crit['car_number'] != '') $this->db->like('lower(s.car_number)', strtolower(trim($crit['car_number'])));
	    if(isset($crit['service_group_id']) && $crit['service_group_id'] != '') $this->db->where('s.service_group_id', $crit['service_group_id']);
	    if(isset($crit['route_line_id']) && $crit['route_line_id'] != '') $this->db->where('s.route_line_id', $crit['route_line_id']);
	    if(isset($crit['manufacture_company_id']) && $crit['manufacture_company_id'] != '') $this->db->where('s.manufacture_company_id', $crit['manufacture_company_id']);
	    if(isset($crit['transport_company_id']) && $crit['transport_company_id'] != '') $this->db->where('s.transport_company_id', $crit['transport_company_id']);
	    if(isset($crit['service_status_id']) && $crit['service_status_id'] != '') $this->db->where('s.service_status_id', $crit['service_status_id']);
	    if(isset($crit['operation_status_id']) && $crit['operation_status_id'] != '') $this->db->where('s.operation_status_id', $crit['operation_status_id']);
	    if(isset($crit['connection_status_id']) && $crit['connection_status_id'] != '') $this->db->where('s.connection_status_id', $crit['connection_status_id']);
	    
	    $rs = $this->db->get($this->table.' s');
	    if($rs->num_rows() > 0) {
	        $row = $rs->row();
	        return intval($row->total);
	    } else return 0;
	}
	
	public function get_paged_list($crit, $sort, $order, $limit = 10, $offset = 0) {
	    $this->db->select('s.*,sg.name as service_group,rl.name as route_line,mc.name as manufacture_company,tc.name as transport_company,ss.name as service_status,os.name as operation_status,cs.name as connection_status');
	    
	    $this->db->join('service_group sg', 's.service_group_id=sg.id', 'left');
	    $this->db->join('route_line rl', 's.route_line_id=rl.id', 'left');
	    $this->db->join('manufacture_company mc', 's.manufacture_company_id=mc.id', 'left');
	    $this->db->join('transport_company tc', 's.transport_company_id=tc.id', 'left');
	    $this->db->join('service_status ss', 's.service_status_id=ss.id', 'left');
	    $this->db->join('operation_status os', 's.operation_status_id=os.id', 'left');
	    $this->db->join('connection_status cs', 's.connection_status_id=cs.id', 'left');
	    
	    if(isset($crit['code']) && $crit['code'] != '') $this->db->like('lower(s.code)', strtolower(trim($crit['code'])));
	    if(isset($crit['name']) && $crit['name'] != '') $this->db->like('lower(s.name)', strtolower(trim($crit['name'])));
	    if(isset($crit['car_number']) && $crit['car_number'] != '') $this->db->like('lower(s.car_number)', strtolower(trim($crit['car_number'])));
	    if(isset($crit['service_group_id']) && $crit['service_group_id'] != '') $this->db->where('s.service_group_id', $crit['service_group_id']);
	    if(isset($crit['route_line_id']) && $crit['route_line_id'] != '') $this->db->where('s.route_line_id', $crit['route_line_id']);
	    if(isset($crit['manufacture_company_id']) && $crit['manufacture_company_id'] != '') $this->db->where('s.manufacture_company_id', $crit['manufacture_company_id']);
	    if(isset($crit['transport_company_id']) && $crit['transport_company_id'] != '') $this->db->where('s.transport_company_id', $crit['transport_company_id']);
	    if(isset($crit['service_status_id']) && $crit['service_status_id'] != '') $this->db->where('s.service_status_id', $crit['service_status_id']);
	    if(isset($crit['operation_status_id']) && $crit['operation_status_id'] != '') $this->db->where('s.operation_status_id', $crit['operation_status_id']);
	    if(isset($crit['connection_status_id']) && $crit['connection_status_id'] != '') $this->db->where('s.connection_status_id', $crit['connection_status_id']);
	    
	    if($sort != '' && $order != '') $this->db->order_by('s.'.$sort, $order);
	    return $this->db->get($this->table.' s', $limit, $offset);
	}
	
	public function get_list($crit, $sort, $order) {
	    $this->db->select('s.*,sg.name as service_group,rl.name as route_line,rl.code as route_line_code,mc.name as manufacture_company,tc.name as transport_company,ss.name as service_status,os.name as operation_status,cs.name as connection_status');
	    
	    $this->db->join('service_group sg', 's.service_group_id=sg.id', 'left');
	    $this->db->join('route_line rl', 's.route_line_id=rl.id', 'left');
	    $this->db->join('manufacture_company mc', 's.manufacture_company_id=mc.id', 'left');
	    $this->db->join('transport_company tc', 's.transport_company_id=tc.id', 'left');
	    $this->db->join('service_status ss', 's.service_status_id=ss.id', 'left');
	    $this->db->join('operation_status os', 's.operation_status_id=os.id', 'left');
	    $this->db->join('connection_status cs', 's.connection_status_id=cs.id', 'left');
	    
	    if(isset($crit['code']) && $crit['code'] != '') $this->db->like('lower(s.code)', strtolower(trim($crit['code'])));
	    if(isset($crit['name']) && $crit['name'] != '') $this->db->like('lower(s.name)', strtolower(trim($crit['name'])));
	    if(isset($crit['car_number']) && $crit['car_number'] != '') $this->db->like('lower(s.car_number)', strtolower(trim($crit['car_number'])));
	    if(isset($crit['service_group_id']) && $crit['service_group_id'] != '') $this->db->where('s.service_group_id', $crit['service_group_id']);
	    if(isset($crit['route_line_id']) && $crit['route_line_id'] != '') $this->db->where('s.route_line_id', $crit['route_line_id']);
	    if(isset($crit['manufacture_company_id']) && $crit['manufacture_company_id'] != '') $this->db->where('s.manufacture_company_id', $crit['manufacture_company_id']);
	    if(isset($crit['transport_company_id']) && $crit['transport_company_id'] != '') $this->db->where('s.transport_company_id', $crit['transport_company_id']);
	    if(isset($crit['service_status_id']) && $crit['service_status_id'] != '') $this->db->where('s.service_status_id', $crit['service_status_id']);
	    if(isset($crit['operation_status_id']) && $crit['operation_status_id'] != '') $this->db->where('s.operation_status_id', $crit['operation_status_id']);
	    if(isset($crit['connection_status_id']) && $crit['connection_status_id'] != '') $this->db->where('s.connection_status_id', $crit['connection_status_id']);
	    
	    if($sort != '' && $order != '') $this->db->order_by('s.'.$sort, $order);
	    return $this->db->get($this->table.' s');
	}
	
	public function search_list($crit, $sort, $order) {
	    $this->db->select('s.id,s.car_number as name');
	    if(isset($crit['code']) && $crit['code'] != '') $this->db->like('lower(s.code)', strtolower(trim($crit['code'])));
	    if(isset($crit['name']) && $crit['name'] != '') $this->db->like('lower(s.name)', strtolower(trim($crit['name'])));
	    if(isset($crit['car_number']) && $crit['car_number'] != '') $this->db->like('lower(s.car_number)', strtolower(trim($crit['car_number'])));
	    if($sort != '' && $order != '') $this->db->order_by('s.'.$sort, $order);
	    return $this->db->get($this->table.' s');
	}
	
	public function get_by_id($id)
	{
	    $this->db->select('s.*,sg.name as service_group,rl.name as route_line,mc.name as manufacture_company,tc.name as transport_company,ss.name as service_status,os.name as operation_status,cs.name as connection_status');
	    $this->db->join('service_group sg', 's.service_group_id=sg.id', 'left');
	    $this->db->join('route_line rl', 's.route_line_id=rl.id', 'left');
	    $this->db->join('manufacture_company mc', 's.manufacture_company_id=mc.id', 'left');
	    $this->db->join('transport_company tc', 's.transport_company_id=tc.id', 'left');
	    $this->db->join('service_status ss', 's.service_status_id=ss.id', 'left');
	    $this->db->join('operation_status os', 's.operation_status_id=os.id', 'left');
	    $this->db->join('connection_status cs', 's.connection_status_id=cs.id', 'left');
	    $this->db->where('s.id', trim($id) );
		return $this->db->get($this->table.' s');
	}
	
	public function get_by_code($code)
	{
	    $this->db->select('s.*,sg.name as service_group,rl.name as route_line,mc.name as manufacture_company,tc.name as transport_company,ss.name as service_status,os.name as operation_status,cs.name as connection_status');
	    $this->db->join('service_group sg', 's.service_group_id=sg.id', 'left');
	    $this->db->join('route_line rl', 's.route_line_id=rl.id', 'left');
	    $this->db->join('manufacture_company mc', 's.manufacture_company_id=mc.id', 'left');
	    $this->db->join('transport_company tc', 's.transport_company_id=tc.id', 'left');
	    $this->db->join('service_status ss', 's.service_status_id=ss.id', 'left');
	    $this->db->join('operation_status os', 's.operation_status_id=os.id', 'left');
	    $this->db->join('connection_status cs', 's.connection_status_id=cs.id', 'left');
	    $this->db->where('s.code', trim($code));
	    return $this->db->get($this->table.' s');
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
	        $new    = str_replace('BID-', '', $last);
	        $val    = intval($new)+1;
	        $str    = "$val";
	        $str    = "BID-".str_repeat('0', 4-strlen($str)).$str;
	        return $str;
	    } else return 'BID-0001';
	}
}
?>