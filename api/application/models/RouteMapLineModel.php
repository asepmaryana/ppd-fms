<?php
class RouteMapLineModel extends CI_Model
{
	public $table	= 'route_map_line';
	
	public function __construct() {
		parent::__construct();
	}
	
	public function get_by_id($id)
	{
		$this->db->where('id', $id);
		return $this->db->get($this->table);
	}
	
	public function get_by_route_map_id($route_map_id)
	{
	    $this->db->where('route_map_id', $route_map_id);
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
	
	public function delete_by_route_map_id($route_map_id)
	{
	    $this->db->where('route_map_id', $route_map_id);
	    return $this->db->delete($this->table);
	}
	
}
?>