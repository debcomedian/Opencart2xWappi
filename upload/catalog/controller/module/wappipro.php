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
                $this->load->library('wappiproreplacements');
                if (!$this->registry->has('wappiproreplacements')) {
                    $this->registry->set('wappiproreplacements', new WappiProReplacements($this->registry));
                }
                $wappiPro = $this->registry->get('wappiproreplacements');$replacements = $wappiPro->getReplacements();
                if (empty($replacements)) {
                    $wappiPro->loadReplacements($orderId);
                    $replacements = $wappiPro->getReplacements();
                }
                if (!empty($replacements)) {
                    $flatOrder = $this->flattenArray($order);
                    foreach ($flatOrder as $key => $value) {
                        $placeholder = $key;
                        if (isset($replacements[$placeholder])) {
                            $statusMessage = str_replace('{' . $placeholder . '}', $value, $statusMessage);
                            error_log('Replaced ' . $placeholder . ' with ' . $value . PHP_EOL, 3, DIR_LOGS . "wappi-errors.log");
                        } else {
                            error_log('Warning: Key ' . $key . ' not found in replacements' . PHP_EOL, 3, DIR_LOGS . "wappi-errors.log");
                        }
                    }
                } else {
                    error_log('Warning: No replacements available for processing' . PHP_EOL, 3, DIR_LOGS . "wappi-errors.log");
                }

                $apiKey = isset($settings['wappipro_apiKey']) ? $settings['wappipro_apiKey'] : null;
                $username = isset($settings['wappipro_username']) ? $settings['wappipro_username'] : null;        

                if (!empty($apiKey)) {
                    if (strlen($username) != 20) {
                        $platform_settings = $this->model_setting_setting->getSetting('wappipro_platform');
                        $platform = isset($platform_settings['wappipro_platform']) ? $platform_settings['wappipro_platform'] : '';
                        
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
                            $wappipro_settings = $this->model_setting_setting->getSetting('wappipro_test');
                            $wappipro_self_phone = isset($wappipro_settings['wappipro_test_phone_number']) ? $wappipro_settings['wappipro_test_phone_number'] : '';

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
                    } else {
                        $req = [
                            'postfields' => json_encode([
                                'recipient' => $order['telephone'],
                                'body' => $statusMessage,
                                'cascade_id' => $username
                            ]),
                            'header' => [
                                "accept: application/json",
                                "Authorization: " .  $apiKey,
                                "Content-Type: application/json",
                            ],
                            'url' => 'https://wappi.pro/csender/cascade/send',
                        ];
                        if ($isSelfSendingActive === 'true') {
                            $wappipro_settings = $this->model_setting_setting->getSetting('wappipro_test');
                            $wappipro_self_phone = isset($wappipro_settings['wappipro_test_phone_number']) ? $wappipro_settings['wappipro_test_phone_number'] : '';

                            if (!empty($wappipro_self_phone)) {
                                $req_self = [
                                    'postfields' => json_encode([
                                        'recipient' => $wappipro_self_phone,
                                        'body' => $statusMessage,
                                        'cascade_id' => $username
                                    ]),
                                    'header' => [
                                        "accept: application/json",
                                        "Authorization: " .  $apiKey,
                                        "Content-Type: application/json",
                                    ],
                                    'url' => 'https://wappi.pro/csender/cascade/send',
                                ];
                                $response = json_decode($this->curlito(false, $req_self), true);
                            }
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

    function flattenArray($array, $prefix = '') {
        $flattened = [];
    
        foreach ($array as $key => $value) {
            $fullKey = $prefix . $key;
            if (is_array($value)) {
                $flattened = array_merge($flattened, $this->flattenArray($value, $fullKey . '_'));
            } else {
                $flattened[$fullKey] = $value;
            }
        }
    
        return $flattened;
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
