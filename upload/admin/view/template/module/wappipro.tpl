<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?php echo htmlspecialchars_decode($heading_title, ENT_QUOTES); ?></h1>
        </div>
    </div>
    <div class="container-fluid">
        <?php if (!empty($error_warning)) { ?>
            <?php foreach ($error_warning as $error) { ?>
                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo htmlspecialchars_decode($error['error'], ENT_QUOTES); ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php } ?>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo htmlspecialchars_decode($text_edit, ENT_QUOTES); ?></h3>
                <h2 class="panel-title"> <?php echo htmlspecialchars_decode($about_title, ENT_QUOTES); ?></h2>
            </div>
            <div class="s-w">
                
            <?php if ($wappipro_test_result === false) { ?>
                    <div class="alert alert-warning wappipro-alert wappipro-alert-failure alert-dismissible show" role="alert">
                        Тест не пройден, проверьте ID ПРОФИЛЯ и ТОКЕН API! <a
                                href="#"
                                type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </a>
                    </div>
                <?php } ?>
                <div class="s-h">
                    <img src="<?php echo htmlspecialchars_decode($wappipro_logo, ENT_QUOTES); ?>" style="height: 100px" alt="wappipro">
                </div>
                <div class="s-b">
                    <div class="group">
                        <label><p><?php echo htmlspecialchars_decode($instructions_title, ENT_QUOTES); ?></p></label>
                        <ul>
                            <li><span class="tag">1</span>
                                <p> <?php echo htmlspecialchars_decode($step_1, ENT_QUOTES); ?></p></li>
                            <li><span class="tag">2</span>
                                <p><?php echo htmlspecialchars_decode($step_2, ENT_QUOTES); ?></p></li>
                            <li><span class="tag">3</span>
                                <p><?php echo htmlspecialchars_decode($step_3, ENT_QUOTES); ?></p></li>
                            <li><span class="tag">4</span>
                                <p><?php echo htmlspecialchars_decode($step_4, ENT_QUOTES); ?></p></li>
                            <li><span class="tag">5</span>
                                <p><?php echo htmlspecialchars_decode($step_5, ENT_QUOTES); ?></p></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="panel-body s-w">
                <form action="<?php echo htmlspecialchars_decode($action, ENT_QUOTES); ?>" method="post" enctype="multipart/form-data" id="form-first-module"
                      class="form-horizontal">

                    <div class="group">
                        <div class="g">
                            <label><?php echo htmlspecialchars_decode($btn_activate_text, ENT_QUOTES); ?></label>
                            <input type="checkbox" name="wappipro_active"
                                   value="true" <?php echo $wappipro_active ? 'checked="checked"' : ''; ?>>
                        </div>
                    </div>

                    <div class="group">
                        <label><?php echo htmlspecialchars_decode($btn_apiKey_text, ENT_QUOTES); ?></label>
                        <input type="text" name="wappipro_apiKey" value="<?php echo !empty($wappipro_apiKey) ? htmlspecialchars_decode($wappipro_apiKey, ENT_QUOTES) : ''; ?>"
                               placeholder="<?php echo htmlspecialchars_decode($btn_apiKey_placeholder, ENT_QUOTES); ?>">
                        <div class="description">
                            <p><?php echo htmlspecialchars_decode($btn_apiKey_description, ENT_QUOTES); ?></p>
                        </div>
                    </div>
                    
                    <div class="group">
                        <label><?php echo htmlspecialchars_decode($btn_username_text, ENT_QUOTES); ?></label>
                        <input type="text" name="wappipro_username" value="<?php echo !empty($wappipro_username) ? htmlspecialchars_decode($wappipro_username, ENT_QUOTES) : ''; ?>"
                               placeholder="<?php echo htmlspecialchars_decode($btn_username_placeholder, ENT_QUOTES); ?>">
                        <div class="description">
                            <p><?php echo htmlspecialchars_decode($btn_username_description, ENT_QUOTES); ?></p>
                        </div>
                    </div>

                    <div class="group">
                        <label><?php echo htmlspecialchars_decode($btn_test_text, ENT_QUOTES); ?></label>
                        <input type="text" name="wappipro_test_phone_number"
                               value="<?php echo !empty($wappipro_test_phone_number) ? htmlspecialchars_decode($wappipro_test_phone_number, ENT_QUOTES) : ''; ?>"
                               placeholder="<?php echo htmlspecialchars_decode($btn_test_placeholder, ENT_QUOTES); ?>">
                        <div class="description">
                            <p><?php echo htmlspecialchars_decode($btn_test_description, ENT_QUOTES); ?></p>
                        </div>
                        <input type="submit" name="wappipro_test" value="<?php echo htmlspecialchars_decode($btn_test_send, ENT_QUOTES); ?>">
                        <div class="g">
                            <label><?php echo htmlspecialchars_decode($btn_wappipro_self_sending_active, ENT_QUOTES); ?></label>
                            <input type="checkbox" name="wappipro_self_sending_active"
                                   value="true" <?php echo $wappipro_self_sending_active ? 'checked="checked"' : ''; ?>>
                        </div>
                    </div>

                    <div class="group">
                        <label><?php echo htmlspecialchars_decode($btn_status_order_canceled, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_canceled_active" value="true" <?php echo $wappipro_canceled_active ? 'checked="checked"' : ''; ?>>
                        <label><?php echo htmlspecialchars_decode($btn_duble_admin, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_admin_canceled_active" value="true" <?php echo $wappipro_admin_canceled_active ? 'checked="checked"' : ''; ?>>
                        <textarea name="wappipro_canceled_message"><?php echo htmlspecialchars_decode($wappipro_canceled_message, ENT_QUOTES); ?></textarea>
                        <div class="description">
                            <p><?php echo htmlspecialchars_decode($btn_status_order_description, ENT_QUOTES); ?></p>
                        </div>
                    </div>

                    <div class="group">
                        <label><?php echo htmlspecialchars_decode($btn_status_order_canceled_reversal, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_canceled_reversal_active" value="true" <?php echo $wappipro_canceled_reversal_active ? 'checked="checked"' : ''; ?>>
                        <label><?php echo htmlspecialchars_decode($btn_duble_admin, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_admin_canceled_reversal_active" value="true" <?php echo $wappipro_admin_canceled_reversal_active ? 'checked="checked"' : ''; ?>>
                        <textarea name="wappipro_canceled_reversal_message"><?php echo htmlspecialchars_decode($wappipro_canceled_reversal_message, ENT_QUOTES); ?></textarea>
                        <div class="description">
                            <p><?php echo htmlspecialchars_decode($btn_status_order_description, ENT_QUOTES); ?></p>
                        </div>
                    </div>

                    <div class="group">
                        <label><?php echo htmlspecialchars_decode($btn_status_order_chargebackd, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_chargeback_active" value="true" <?php echo $wappipro_chargeback_active ? 'checked="checked"' : ''; ?>>
                        <label><?php echo htmlspecialchars_decode($btn_duble_admin, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_admin_chargeback_active" value="true" <?php echo $wappipro_admin_chargeback_active ? 'checked="checked"' : ''; ?>>
                        <textarea name="wappipro_chargeback_message"><?php echo htmlspecialchars_decode($wappipro_chargeback_message, ENT_QUOTES); ?></textarea>
                        <div class="description">
                            <p><?php echo htmlspecialchars_decode($btn_status_order_description, ENT_QUOTES); ?></p>
                        </div>
                    </div>

                    <div class="group">
                        <label><?php echo htmlspecialchars_decode($btn_status_order_complete, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_complete_active" value="true" <?php echo $wappipro_complete_active ? 'checked="checked"' : ''; ?>>
                        <label><?php echo htmlspecialchars_decode($btn_duble_admin, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_admin_complete_active" value="true" <?php echo $wappipro_admin_complete_active ? 'checked="checked"' : ''; ?>>
                        <textarea name="wappipro_complete_message"><?php echo htmlspecialchars_decode($wappipro_complete_message, ENT_QUOTES); ?></textarea>
                        <div class="description">
                            <p><?php echo htmlspecialchars_decode($btn_status_order_description, ENT_QUOTES); ?></p>
                        </div>
                    </div>

                    <div class="group">
                        <label><?php echo htmlspecialchars_decode($btn_status_order_denied, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_denied_active" value="true" <?php echo $wappipro_denied_active ? 'checked="checked"' : ''; ?>>
                        <label><?php echo htmlspecialchars_decode($btn_duble_admin, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_admin_denied_active" value="true" <?php echo $wappipro_admin_denied_active ? 'checked="checked"' : ''; ?>>
                        <textarea name="wappipro_denied_message"><?php echo htmlspecialchars_decode($wappipro_denied_message, ENT_QUOTES); ?></textarea>
                        <div class="description">
                            <p><?php echo htmlspecialchars_decode($btn_status_order_description, ENT_QUOTES); ?></p>
                        </div>
                    </div>

                    <div class="group">
                        <label><?php echo htmlspecialchars_decode($btn_status_order_expired, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_expired_active" value="true" <?php echo $wappipro_expired_active ? 'checked="checked"' : ''; ?>>
                        <label><?php echo htmlspecialchars_decode($btn_duble_admin, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_admin_expired_active" value="true" <?php echo $wappipro_admin_expired_active ? 'checked="checked"' : ''; ?>>
                        <textarea name="wappipro_expired_message"><?php echo htmlspecialchars_decode($wappipro_expired_message, ENT_QUOTES); ?></textarea>
                        <div class="description">
                            <p><?php echo htmlspecialchars_decode($btn_status_order_description, ENT_QUOTES); ?></p>
                        </div>
                    </div>

                    <div class="group">
                        <label><?php echo htmlspecialchars_decode($btn_status_order_failed, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_failed_active" value="true" <?php echo $wappipro_failed_active ? 'checked="checked"' : ''; ?>>
                        <label><?php echo htmlspecialchars_decode($btn_duble_admin, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_admin_failed_active" value="true" <?php echo $wappipro_admin_failed_active ? 'checked="checked"' : ''; ?>>
                        <textarea name="wappipro_failed_message"><?php echo htmlspecialchars_decode($wappipro_failed_message, ENT_QUOTES); ?></textarea>
                        <div class="description">
                            <p><?php echo htmlspecialchars_decode($btn_status_order_description, ENT_QUOTES); ?></p>
                        </div>
                    </div>

                    <div class="group">
                        <label><?php echo htmlspecialchars_decode($btn_status_order_pending, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_pending_active" value="true" <?php echo $wappipro_pending_active ? 'checked="checked"' : ''; ?>>
                        <label><?php echo htmlspecialchars_decode($btn_duble_admin, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_admin_pending_active" value="true" <?php echo $wappipro_admin_pending_active ? 'checked="checked"' : ''; ?>>
                        <textarea name="wappipro_pending_message"><?php echo htmlspecialchars_decode($wappipro_pending_message, ENT_QUOTES); ?></textarea>
                        <div class="description">
                            <p><?php echo htmlspecialchars_decode($btn_status_order_description, ENT_QUOTES); ?></p>
                        </div>
                    </div>

                    <div class="group">
                        <label><?php echo htmlspecialchars_decode($btn_status_order_processed, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_processed_active" value="true" <?php echo $wappipro_processed_active ? 'checked="checked"' : ''; ?>>
                        <label><?php echo htmlspecialchars_decode($btn_duble_admin, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_admin_processed_active" value="true" <?php echo $wappipro_admin_processed_active ? 'checked="checked"' : ''; ?>>
                        <textarea name="wappipro_processed_message"><?php echo htmlspecialchars_decode($wappipro_processed_message, ENT_QUOTES); ?></textarea>
                        <div class="description">
                            <p><?php echo htmlspecialchars_decode($btn_status_order_description, ENT_QUOTES); ?></p>
                        </div>
                    </div>

                    <div class="group">
                        <label><?php echo htmlspecialchars_decode($btn_status_order_processing, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_processing_active" value="true" <?php echo $wappipro_processing_active ? 'checked="checked"' : ''; ?>>
                        <label><?php echo htmlspecialchars_decode($btn_duble_admin, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_admin_processing_active" value="true" <?php echo $wappipro_admin_processing_active ? 'checked="checked"' : ''; ?>>
                        <textarea name="wappipro_processing_message"><?php echo htmlspecialchars_decode($wappipro_processing_message, ENT_QUOTES); ?></textarea>
                        <div class="description">
                            <p><?php echo htmlspecialchars_decode($btn_status_order_description, ENT_QUOTES); ?></p>
                        </div>
                    </div>

                    <div class="group">
                        <label><?php echo htmlspecialchars_decode($btn_status_order_refunded, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_refunded_active" value="true" <?php echo $wappipro_refunded_active ? 'checked="checked"' : ''; ?>>
                        <label><?php echo htmlspecialchars_decode($btn_duble_admin, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_admin_refunded_active" value="true" <?php echo $wappipro_admin_refunded_active ? 'checked="checked"' : ''; ?>>
                        <textarea name="wappipro_refunded_message"><?php echo htmlspecialchars_decode($wappipro_refunded_message, ENT_QUOTES); ?></textarea>
                        <div class="description">
                            <p><?php echo htmlspecialchars_decode($btn_status_order_description, ENT_QUOTES); ?></p>
                        </div>
                    </div>

                    <div class="group">
                        <label><?php echo htmlspecialchars_decode($btn_status_order_reversed, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_reversed_active" value="true" <?php echo $wappipro_reversed_active ? 'checked="checked"' : ''; ?>>
                        <label><?php echo htmlspecialchars_decode($btn_duble_admin, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_admin_reversed_active" value="true" <?php echo $wappipro_admin_reversed_active ? 'checked="checked"' : ''; ?>>
                        <textarea name="wappipro_reversed_message"><?php echo htmlspecialchars_decode($wappipro_reversed_message, ENT_QUOTES); ?></textarea>
                        <div class="description">
                            <p><?php echo htmlspecialchars_decode($btn_status_order_description, ENT_QUOTES); ?></p>
                        </div>
                    </div>

                    <div class="group">
                        <label><?php echo htmlspecialchars_decode($btn_status_order_shipped, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_shipped_active" value="true" <?php echo $wappipro_shipped_active ? 'checked="checked"' : ''; ?>>
                        <label><?php echo htmlspecialchars_decode($btn_duble_admin, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_admin_shipped_active" value="true" <?php echo $wappipro_admin_shipped_active ? 'checked="checked"' : ''; ?>>
                        <textarea name="wappipro_shipped_message"><?php echo htmlspecialchars_decode($wappipro_shipped_message, ENT_QUOTES); ?></textarea>
                        <div class="description">
                            <p><?php echo htmlspecialchars_decode($btn_status_order_description, ENT_QUOTES); ?></p>
                        </div>
                    </div>

                    <div class="group">
                        <label><?php echo htmlspecialchars_decode($btn_status_order_voided, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_voided_active" value="true" <?php echo $wappipro_voided_active ? 'checked="checked"' : ''; ?>>
                        <label><?php echo htmlspecialchars_decode($btn_duble_admin, ENT_QUOTES); ?></label>
                        <input type="checkbox" name="wappipro_admin_voided_active" value="true" <?php echo $wappipro_admin_voided_active ? 'checked="checked"' : ''; ?>>
                        <textarea name="wappipro_voided_message"><?php echo htmlspecialchars_decode($wappipro_voided_message, ENT_QUOTES); ?></textarea>
                        <div class="description">
                            <p><?php echo htmlspecialchars_decode($btn_status_order_description, ENT_QUOTES); ?></p>
                        </div>
                    </div>
                    <input type="submit" name="wappipro_save_settings" value="<?php echo htmlspecialchars_decode($btn_token_save_all, ENT_QUOTES); ?>">
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>
