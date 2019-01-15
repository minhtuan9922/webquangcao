<?php
class ControllerCommonColumnLeft extends Controller {
	private $id = 'd_blog_module';
    private $route = 'extension/d_blog_module/category';
    private $sub_versions = array('lite', 'light', 'free');
    private $mbooth = '';
    private $prefix = '';
    private $config_file = '';
    private $error = array();
    private $debug = false;
    private $setting = array();

    public function __construct($registry) {
        parent::__construct($registry);
        if(!isset($this->user)){
            if(VERSION >= '2.2.0.0'){
                $this->user = new Cart\User($registry);
            }else{
                $this->user = new User($registry);
            }

            $this->load->model('extension/d_opencart_patch/load');
            $this->theme = $this->config->get($this->config->get('config_theme').'_directory');
        }

        $this->load->language('extension/d_blog_module/category');

        $this->load->model('extension/module/d_blog_module');
        $this->load->model('extension/d_blog_module/category');
        $this->load->model('extension/d_blog_module/post');
        $this->load->model('tool/image');

        $this->session->data['d_blog_module_debug'] = $this->config->get('d_blog_module_debug');
        $this->config_file = $this->model_extension_module_d_blog_module->getConfigFile($this->id, $this->sub_versions);
        $this->setting = $this->model_extension_module_d_blog_module->getConfigData($this->id, $this->id . '_setting', $this->config->get('config_store_id'), $this->config_file);
    }
	
	public function index() {
		$this->load->model('design/layout');

		if (isset($this->request->get['route'])) {
			$route = (string)$this->request->get['route'];
		} else {
			$route = 'common/home';
		}

		$layout_id = 0;

		if ($route == 'product/category' && isset($this->request->get['path'])) {
			$this->load->model('catalog/category');

			$path = explode('_', (string)$this->request->get['path']);

			$layout_id = $this->model_catalog_category->getCategoryLayoutId(end($path));
		}

		if ($route == 'product/product' && isset($this->request->get['product_id'])) {
			$this->load->model('catalog/product');

			$layout_id = $this->model_catalog_product->getProductLayoutId($this->request->get['product_id']);
		}

		if ($route == 'information/information' && isset($this->request->get['information_id'])) {
			$this->load->model('catalog/information');

			$layout_id = $this->model_catalog_information->getInformationLayoutId($this->request->get['information_id']);
		}

		if (!$layout_id) {
			$layout_id = $this->model_design_layout->getLayout($route);
		}

		if (!$layout_id) {
			$layout_id = $this->config->get('config_layout_id');
		}

		$this->load->model('setting/module');

		$data['modules'] = array();

		$modules = $this->model_design_layout->getLayoutModules($layout_id, 'column_left');

		foreach ($modules as $module) {
			$part = explode('.', $module['code']);

			if (isset($part[0]) && $this->config->get('module_' . $part[0] . '_status')) {
				$module_data = $this->load->controller('extension/module/' . $part[0]);

				if ($module_data) {
					$data['modules'][] = $module_data;
				}
			}

			if (isset($part[1])) {
				$setting_info = $this->model_setting_module->getModule($part[1]);

				if ($setting_info && $setting_info['status']) {
					$output = $this->load->controller('extension/module/' . $part[0], $setting_info);

					if ($output) {
						$data['modules'][] = $output;
					}
				}
			}
		}
		
		$data['telephone'] = $this->config->get('config_telephone');
		$data['email'] = $this->config->get('config_email');
		$data['address'] = $this->config->get('config_address');
		
		return $this->load->view('common/column_left', $data);
	}
}
