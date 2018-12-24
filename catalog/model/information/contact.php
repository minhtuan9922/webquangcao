<?php
class ModelInformationContact extends Model {
	public function add_contact($data = array()) {
		$this->db->query("insert into " . DB_PREFIX . "contact set name = '" . $this->db->escape($data['name']) . "', email = '". $this->db->escape($data['email']) ."', enquiry = '". $this->db->escape($data['enquiry']) ."', date_added = now()");

		return $query->row;
	}
}