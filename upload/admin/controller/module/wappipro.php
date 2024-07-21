<?php

class ControllerModuleWappiPro extends Controller
{
    private $error       = [];
    private $code        = ['wappipro_test', 'wappipro'];
    public  $testResult  = true;
    private $fields_test = [
        "wappipro_test_phone_number" => [
            "label"    => "Phone Number",
            "type"     => "isPhoneNumber",
            "value"    => "",
            "validate" => true,
        ],
    ];
    private $fields = [
        "wappipro_username" => ["label" => "Username", "type" => "isEmpty", "value" => "", "validate" => true],
        "wappipro_apiKey"   => ["label" => "API Key", "type" => "isEmpty", "value" => "", "validate" => true],
    ];

    public function index()
    {
        if (!$this->isModuleEnabled()) {
            $this->response->redirect(
                $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
            );
            exit;
        }

        $this->load->language('module/wappipro');

        $this->document->setTitle($this->language->get('heading_title'));
        $this->document->addStyle('/admin/view/stylesheet/wappipro/wappipro.css');

        $this->load->model('setting/setting');
        $this->load->model('design/layout');
        $this->load->model('module/wappipro/validator');
        $this->load->model('module/wappipro/helper');
        $this->load->model('localisation/order_status');

        $data['payment_time_string'] = '';

        $this->submitted($data);
        $this->loadFieldsToData($data);

        $data['action'] = $this->url->link('module/wappipro', 'token=' . $this->session->data['token'], 'SSL');

        $data['error_warning'] = $this->error;

        $data['wappipro_logo'] = '/admin/view/image/wappipro/logo.jpg';

        $data['about_title'] = $this->language->get('about_title');
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_edit'] = $this->language->get('text_edit');

        $data['btn_test_text'] = $this->language->get('btn_test_text');
        $data['btn_test_placeholder'] = $this->language->get('btn_test_placeholder');
        $data['btn_test_description'] = $this->language->get('btn_test_description');
        $data['btn_test_send'] = $this->language->get('btn_test_send');

        $data['btn_wappipro_self_sending_active'] = $this->language->get('btn_wappipro_self_sending_active');

        $data['btn_apiKey_text'] = $this->language->get('btn_apiKey_text');
        $data['btn_apiKey_placeholder'] = $this->language->get('btn_apiKey_placeholder');
        $data['btn_apiKey_description'] = $this->language->get('btn_apiKey_description');
        $data['btn_duble_admin'] = $this->language->get('btn_duble_admin');

        $data['btn_username_text'] = $this->language->get('btn_username_text');
        $data['btn_username_placeholder'] = $this->language->get('btn_username_placeholder');
        $data['btn_username_description'] = $this->language->get('btn_username_description');

        $data['btn_token_save_all'] = $this->language->get('btn_token_save_all');

        $data['btn_status_order_description'] = $this->language->get('btn_status_order_description');

        $data['instructions_title'] = $this->language->get('instructions_title');
        $data['step_1'] = $this->language->get('step_1');
        $data['step_2'] = $this->language->get('step_2');
        $data['step_3'] = $this->language->get('step_3');
        $data['step_4'] = $this->language->get('step_4');
        $data['step_5'] = $this->language->get('step_5');

        $data['order_status_list'] = $this->model_localisation_order_status->getOrderStatuses();
        $data['wappipro_test_result'] = $this->testResult;

        $settings = $this->model_setting_setting->getSetting('wappipro');
        $data['wappipro_order_status_active'] = [];
        $data['wappipro_order_status_message'] = [];
        $data['wappipro_admin_order_status_active'] = [];

        foreach ($data['order_status_list'] as $status) {
            $data['wappipro_order_status_active'][$status['order_status_id']] = isset($settings['wappipro_' . $status['order_status_id'] . '_active']) ? $settings['wappipro_' . $status['order_status_id'] . '_active'] : '';
            $data['wappipro_order_status_message'][$status['order_status_id']] = isset($settings['wappipro_' . $status['order_status_id'] . '_message']) ? $settings['wappipro_' . $status['order_status_id'] . '_message'] : '';
            $data['wappipro_admin_order_status_active'][$status['order_status_id']] = isset($settings['wappipro_admin_' . $status['order_status_id'] . '_active']) ? $settings['wappipro_admin_' . $status['order_status_id'] . '_active'] : '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('module/wappipro.tpl', $data));
    }

    public function isModuleEnabled()
    {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "extension WHERE code = 'wappipro'");
        if ($query->num_rows) {
            return true;
        }

        return false;
    }

    public function submitted(&$data)
    {
        if (!empty($_POST)) {
            $this->fields_test['wappipro_test_phone_number']['value'] = isset($_POST['wappipro_test_phone_number']) ? $_POST['wappipro_test_phone_number'] : '';
            
            if (!empty($_POST['wappipro_test'])) {
                $this->validateFields();
                if (empty($_POST['wappipro_apiKey'])) {
                    $this->error[] = ["error" => $this->language->get('err_apikey')];
                }

                if (empty($_POST['wappipro_username'])) {
                    $this->error[] = ["error" => $this->language->get('err_profile')];
                }

                if (empty($this->error)) {
                    $this->saveFiledsToDB();
                    $settings = $this->model_setting_setting->getSetting('wappipro');
                    $phone = $this->model_setting_setting->getSetting('wappipro_test')['wappipro_test_phone_number'];

                    $message = $this->language->get('test_message');

                    $data_profile = $this->model_module_wappipro_helper->get_profile_info($settings);
                    if (isset($data_profile['error'])) {
                        $this->testResult = false;
                        $data["payment_time_string"] = $this->language->get('unvalid_profile');
                    } else {
                        $platform = $data_profile['platform'];
                        if ($platform !== false) {
                            $this->model_module_wappipro_helper->_save_user($settings);
                            $data["payment_time_string"] = $data_profile["payment_time_string"];
                
                            $this->model_setting_setting->editSetting("wappipro_platform", array('wappipro_platform' => $platform));
                
                            $this->testResult = $this->model_module_wappipro_helper->sendTestSMS($settings, $phone, $message);
                        } else {
                            $this->testResult = false;
                            $this->error[] = ["error" => $this->language->get('err_request')];
                        }
                    }
                }
            } else {
                $this->testResult = true;
                $this->validateFields();
                if (empty($this->error)) {
                    $this->saveFiledsToDB();
                }
            }

            return true;
        }

        return false;
    }

    public function loadFieldsToData(&$data)
    {
        $settings = $this->model_setting_setting->getSetting('wappipro');
        $settings_test = $this->model_setting_setting->getSetting('wappipro_test');

        foreach ($this->fields as $key => $value) {
            $data[$key] = isset($settings[$key]) ? $settings[$key] : '';
        }

        foreach ($this->fields_test as $key => $value) {
            $data[$key] = isset($settings_test[$key]) ? $settings_test[$key] : '';
        }

        $order_status_list = $this->model_localisation_order_status->getOrderStatuses();
        foreach ($order_status_list as $status) {
            $data['wappipro_' . $status['order_status_id'] . '_active'] = isset($settings['wappipro_' . $status['order_status_id'] . '_active']) ? $settings['wappipro_' . $status['order_status_id'] . '_active'] : '';
            $data['wappipro_' . $status['order_status_id'] . '_message'] = isset($settings['wappipro_' . $status['order_status_id'] . '_message']) ? $settings['wappipro_' . $status['order_status_id'] . '_message'] : '';
            $data['wappipro_admin_' . $status['order_status_id'] . '_active'] = isset($settings['wappipro_admin_' . $status['order_status_id'] . '_active']) ? $settings['wappipro_admin_' . $status['order_status_id'] . '_active'] : '';
        }
    }

    public function saveFiledsToDB()
    {
        foreach (array_keys($this->fields) as $key) {
            $this->fields[$key] = isset($_POST[$key]) ? $_POST[$key] : '';
        }

        $order_status_list = $this->model_localisation_order_status->getOrderStatuses();
        foreach ($order_status_list as $status) {
            $this->fields['wappipro_' . $status['order_status_id'] . '_message'] = isset($_POST['wappipro_' . $status['order_status_id'] . '_message']) ? $_POST['wappipro_' . $status['order_status_id'] . '_message'] : '';
            $this->fields['wappipro_' . $status['order_status_id'] . '_active'] = isset($_POST['wappipro_' . $status['order_status_id'] . '_active']) ? 'true' : 'false';
            $this->fields['wappipro_admin_' . $status['order_status_id'] . '_active'] = isset($_POST['wappipro_admin_' . $status['order_status_id'] . '_active']) ? 'true' : 'false';
        }

        $this->model_setting_setting->editSetting('wappipro', $this->fields);
        $test_settings = ['wappipro_test_phone_number' => $this->fields_test['wappipro_test_phone_number']['value']];
        $this->model_setting_setting->editSetting('wappipro_test', $test_settings); 
    }

    public function validateFields()
    {
        foreach ($this->fields as $key => $value) {
            if (isset($value['validate'])) {
                $result = call_user_func_array(
                    [$this->model_module_wappipro_validator, $value['type']],
                    [$_POST[$key]]
                );
                if (!$result) {
                    $this->error[] = ["error" => $this->language->get('err_part1') . $value['label'] . $this->language->get('err_part2')];
                }
            }
        }
    }

    public function install()
    {
        $this->db->query("INSERT INTO `" . DB_PREFIX . "event` SET `code` = 'wappipro', `trigger` = 'post.order.history.add', `action` = 'module/wappipro/status_change'");
    }

    public function uninstall()
    {
        $this->load->model('setting/setting');
        $this->db->query("DELETE FROM `" . DB_PREFIX . "event` WHERE `code` = 'wappipro'");
        $this->model_setting_setting->deleteSetting('wappipro');
        $this->model_setting_setting->deleteSetting('wappipro_test');
        $this->model_setting_setting->deleteSetting('wappipro_platform');
    }
}
