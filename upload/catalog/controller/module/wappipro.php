<?php

class ControllerModuleWappiPro extends Controller
{
    public function status_change($orderId)
    {

        $this->load->model('setting/setting');
        $this->load->model('checkout/order');
        $this->load->model('module/wappipro/order');

        $settings = $this->model_setting_setting->getSetting('wappipro');
        $order        = $this->model_checkout_order->getOrder($orderId);
        $orderStatusId = $order['order_status_id'];
        
        $statusName   = $this->model_module_wappipro_order->getStatusName($orderStatusId);
        $isActive = isset($settings['wappipro_active']) ? $settings['wappipro_active'] : null;
        $isSelfSendingActive = isset($settings['wappipro_self_sending_active']) ? $settings['wappipro_self_sending_active'] : null;
        $language = $this->config->get('config_language');


        if ($this->isModuleEnabled() && !empty($isActive) && !empty($statusName)) {

            $status_name = "";

            if (strpos($statusName, "Canceled Reversal") !== false) {
                $status_name = "canceled_reversal";
            } else {
                $status_name = strtolower($statusName);
            }

            $isAdminSend = isset($settings["wappipro_admin_" . $status_name . "_active"]) ? $settings["wappipro_admin_" . $status_name . "_active"] : null;
            $statusActivate = isset($settings["wappipro_" . $status_name . "_active"]) ? $settings["wappipro_" . $status_name . "_active"] : null;
            $statusMessage = isset($settings["wappipro_" . $status_name . "_message"]) ? $settings["wappipro_" . $status_name . "_message"] : null;  

            if (!empty($statusActivate) && !empty($statusMessage)) {
                $replace = [
                    '{order_number}'       => $order['order_id'],
                    '{order_date}'         => $order['date_added'],
                    '{order_total}'        => round(
                        $order['total'] * $order['currency_value'],
                        2
                    ) . ' ' . $order['currency_code'],
                    '{billing_first_name}' => $order['payment_firstname'],
                    '{billing_last_name}'  => $order['payment_lastname'],
                    '{shipping_method}'    => $order['shipping_method'],
                ];

                foreach ($replace as $key => $value) {
                    $statusMessage = str_replace($key, $value, $statusMessage);
                }

                $apiKey = isset($settings['wappipro_apiKey']) ? $settings['wappipro_apiKey'] : null;
                $username = isset($settings['wappipro_username']) ? $settings['wappipro_username'] : null;        

                if (!empty($apiKey)) {

                    $platform = $this->model_setting_setting->getSetting('wappipro_platform')['wappipro_platform'];

                    $req = array();
                    $req['postfields'] = json_encode(array(
                        'recipient' => $order['telephone'],
                        'body' => $statusMessage,
                    ));

                    $req['header'] = array(
                        "accept: application/json",
                        "Authorization: " .  $apiKey,
                        "Content-Type: application/json",
                    );

                    $req['url'] = 'https://wappi.pro/'. $platform . 'api/sync/message/send?profile_id=' . $username;

                    if (!empty($isSelfSendingActive)) {
                        $wappipro_self_phone = $this->model_setting_setting->getSetting('wappipro_test')["wappipro_test_phone_number"];

                        if ($wappipro_self_phone != "" && !empty($isAdminSend)) {
                            $req_self = array();
                            $req_self['postfields'] = json_encode(array(
                                'recipient' => $wappipro_self_phone,
                                'body' => $statusMessage,
                            ));

                            $req_self['header'] = array(
                                "accept: application/json",
                                "Authorization: " .  $apiKey,
                                "Content-Type: application/json",
                            );

                            $req_self['url'] = 'https://wappi.pro/'. $platform . 'api/sync/message/send?profile_id=' . $username;
                            $response = json_decode($this->curlito(false, $req_self), true);
                        }
                    }

                    try {
                        $response = json_decode($this->curlito(false, $req), true);
                    } catch (Exception $e) {
                        var_dump($e->getMessage());
                        die();
                    }
                }
            }
        }
    }

    public function isModuleEnabled()
    {
        $sql    = "SELECT * FROM " . DB_PREFIX . "extension WHERE code = 'wappipro'";
        $result = $this->db->query($sql);
        if ($result->num_rows) {
            return true;
        }

        return false;
    }

    private function curlito($wait, $req, $method = '')
    {

        $curl = curl_init();
        $option = array(
            CURLOPT_URL => $req['url'],
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $req['postfields'],
            CURLOPT_HTTPHEADER => $req['header'],
        );

        if ($wait) {
            $option[CURLOPT_TIMEOUT] = 30;
        } else {
            $option[CURLOPT_TIMEOUT_MS] = 5000;
            $option[CURLOPT_HEADER] = 0;
        }

        curl_setopt_array($curl, $option);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);


        if ($err) {
            error_log("cURL Error #:" . $err . PHP_EOL, 3, DIR_LOGS . 'wappi-errors.log');
            return "cURL Error";
        } else {
            return $response;
        }
    }
}
