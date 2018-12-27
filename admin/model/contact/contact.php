<?php
class ModelContactContact extends Model {

	public function getContact($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "contact order by date_added desc";

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	public function getTotalContact($data = array()) {
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "contact";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	public function deleteContact($contact_id) {
		$sql = "delete FROM " . DB_PREFIX . "contact where contact_id = '".$contact_id."'";

		$this->db->query($sql);
	}
	public function updateStatusContact() {
		$sql = "update " . DB_PREFIX . "contact set status = 1 where status = 0";

		$this->db->query($sql);
	}
	public function countStatusContact($status) {
		$sql = "select count(*) as total from " . DB_PREFIX . "contact where status = '".$status."'";

		$query = $this->db->query($sql);
		return $query->row['total'];
	}
}
