<?php
class ControllerCommonContact extends Controller {
	private $error = array();

	public function index() {

		$this->document->setTitle('Danh sách liên hệ');

		$this->load->model('contact/contact');

		$this->getList();
	}
	public function delete() {

		$this->document->setTitle('Xóa liên hệ');

		$this->load->model('contact/contact');

		if (isset($this->request->get['contact_id'])) {
			$this->model_contact_contact->deleteContact($this->request->get['contact_id']);

			$this->session->data['success'] = 'Bạn đã xóa thành công';

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('common/contact', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Danh sách liên hệ',
			'href' => $this->url->link('contact/contact', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$filter_data = array(
			'start'                    => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                    => $this->config->get('config_limit_admin')
		);

		$this->model_contact_contact->updateStatusContact();
			
		$contact_total = $this->model_contact_contact->getTotalContact($filter_data);

		$results = $this->model_contact_contact->getContact($filter_data);

		$data['contacts'] = array();
		foreach ($results as $result) {			
			$data['contacts'][] = array(
				'contact_id'    	=> $result['contact_id'],
				'name'           	=> $result['name'],
				'email'          	=> $result['email'],
				'enquiry' 			=> $result['enquiry'],
				'status' 			=> $result['status'] == 0 ? 'Chưa xem' : 'Đã xem',
				'date_added'     	=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'delete'           	=> $this->url->link('common/contact/delete', 'user_token=' . $this->session->data['user_token'] . '&contact_id=' . $result['contact_id'] . $url, true)
			);
		}

		$data['user_token'] = $this->session->data['user_token'];
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/contact', $data));
	}
}