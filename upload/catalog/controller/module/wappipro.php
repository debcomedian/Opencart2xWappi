<?php

class ControllerModuleWappiPro extends Controller
{
    public function status_change($orderId)
    {
        $this->load->model('setting/setting');
        $this->load->model('checkout/order');
        $this->load->model('module/wappipro/order');
        $settings = $this->model_setting_setting->getSetting('wappipro');
        $order = $this->model_checkout_order->getOrder($orderId);
        $orderStatusId = $order['order_status_id'];
        $statusNameRow = $this->getOrderStatus($orderStatusId);
        $statusName = isset($statusNameRow['name']) ? $statusNameRow['name'] : null;
        $isSelfSendingActive = isset($settings["wappipro_admin_" . $orderStatusId . "_active"]) ? $settings["wappipro_admin_" . $orderStatusId . "_active"] : '';

        if ($this->isModuleEnabled() && !empty($statusName)) {
            $statusActivate = isset($settings["wappipro_" . $orderStatusId . "_active"]) ? $settings["wappipro_" . $orderStatusId . "_active"] : '';
            $statusMessage = isset($settings["wappipro_" . $orderStatusId . "_message"]) ? $settings["wappipro_" . $orderStatusId . "_message"] : '';

            if (!empty($statusActivate) && !empty($statusMessage)) {
                $replace = [
                    '{order_number}' => $order['order_id'],
                    '{order_date}' => $order['date_added'],
                    '{order_total}' => round($order['total'] * $order['currency_value'], 2) . ' ' . $order['currency_code'],
                    '{billing_first_name}' => $order['payment_firstname'],
                    '{billing_last_name}' => $order['payment_lastname'],
                    '{lastname}' => $order['lastname'],
                    '{firstname}' => $order['firstname'],
                    '{shipping_method}' => isset($order['shipping_method']) ? $order['shipping_method'] : '',
                ];

                foreach ($replace as $key => $value) {
                    $statusMessage = str_replace($key, $value, $statusMessage);
                }

                $apiKey = isset($settings['wappipro_apiKey']) ? $settings['wappipro_apiKey'] : null;
                $username = isset($settings['wappipro_username']) ? $settings['wappipro_username'] : null;        

                if (!empty($apiKey)) {
                    $platform = isset($this->model_setting_setting->getSetting('wappipro_platform')['wappipro_platform']) ? $this->model_setting_setting->getSetting('wappipro_platform')['wappipro_platform'] : '';

                    $req = [
                        'postfields' => json_encode([
                            'recipient' => $order['telephone'],
                            'body' => $statusMessage,
                        ]),
                        'header' => [
                            "accept: application/json",
                            "Authorization: " .  $apiKey,
                            "Content-Type: application/json",
                        ],
                        'url' => 'https://wappi.pro/' . $platform . 'api/sync/message/send?profile_id=' . $username,
                    ];

                    if ($isSelfSendingActive === 'true') {
                        $wappipro_self_phone = isset($this->model_setting_setting->getSetting('wappipro_test')["wappipro_test_phone_number"]) ? $this->model_setting_setting->getSetting('wappipro_test')["wappipro_test_phone_number"] : '';
                        if (!empty($wappipro_self_phone)) {
                            $req_self = [
                                'postfields' => json_encode([
                                    'recipient' => $wappipro_self_phone,
                                    'body' => $statusMessage,
                                ]),
                                'header' => [
                                    "accept: application/json",
                                    "Authorization: " .  $apiKey,
                                    "Content-Type: application/json",
                                ],
                                'url' => 'https://wappi.pro/' . $platform . 'api/sync/message/send?profile_id=' . $username,
                            ];
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
        return $result->num_rows;
    }

    private function getOrderStatus($orderStatusId)
    {
        $query = $this->db->query("SELECT name FROM " . DB_PREFIX . "order_status WHERE order_status_id = '" . (int)$orderStatusId . "' AND language_id = '" . (int)$this->config->get('config_language_id') . "'");
        return $query->row;
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
            error_log($err . PHP_EOL, 3, DIR_LOGS . "wappi-errors.log");
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }
}
