<?php
class ModelToolOnline extends Model {
	public function addOnline($ip, $customer_id, $url, $referer) {
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		//$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_online` WHERE date_added < '" . date('Y-m-d H:i:s', strtotime('-1 hour')) . "'");
		$check = $this->db->query("select * FROM `" . DB_PREFIX . "customer_online` WHERE date_added > '" . date('Y-m-d H:i:s', strtotime('-1 hour')) . "' and ip = '" . $this->db->escape($ip). "' and customer_id = '". (int)$customer_id ."'")->rows;
		
		if(empty($check))
		{
			$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_online` SET `ip` = '" . $this->db->escape($ip) . "', `customer_id` = '" . (int)$customer_id . "', `url` = '" . $this->db->escape($url) . "', `referer` = '" . $this->db->escape($referer) . "', `date_added` = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
		}
	}
}
