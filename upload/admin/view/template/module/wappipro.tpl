<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?php echo $heading_title; ?></h1>
        </div>
    </div>
    <div class="container-fluid">
        <?php if (!empty($error_warning)) { ?>
            <?php foreach ($error_warning as $error) { ?>
                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error['error']; ?>
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            <?php } ?>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
                <h4 class="panel-title"><?php echo $about_title; ?></h4>
            </div>
            <div class="s-w">
                <?php if ($payment_time_string != null) { ?>
                    <?php if ($wappipro_test_result == false) { ?>
                        <div class="alert alert-warning wappipro-alert wappipro-alert-failure alert-dismissible show" role="alert">
                            <?php echo $payment_time_string; ?> 
                            <a href="#" type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </a>
                        </div>
                    <?php } else { ?>
                        <div class="alert alert-success wappipro-alert wappipro-alert-success alert-dismissible show" role="alert" style="background-color: green; color: white;">
                            <?php echo $payment_time_string; ?>
                            <a href="#" type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true" style="color: white;">×</span>
                            </a>
                        </div>
                    <?php } ?>
                <?php } ?>
                <div class="s-h">
                    <img src="<?php echo $wappipro_logo; ?>" style="height: 50px" alt="wappipro">
                </div>
                <div class="s-b">
                    <div class="group">
                        <label><p><?php echo $instructions_title; ?></p></label>
                        <ul>
                            <li><span class="tag">1</span>
                                <p><?php echo $step_1; ?></p></li>
                            <li><span class="tag">2</span>
                                <p><?php echo $step_2; ?></p></li>
                            <li><span class="tag">3</span>
                                <p><?php echo $step_3; ?></p></li>
                            <li><span class="tag">4</span>
                                <p><?php echo $step_4; ?></p></li>
                            <li><span class="tag">5</span>
                                <p><?php echo $step_5; ?></p></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="panel-body s-w">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-first-module" class="form-horizontal">
                    <div class="group">
                        <label><?php echo $btn_apiKey_text; ?></label>
                        <input type="text" name="wappipro_apiKey" value="<?php echo $wappipro_apiKey; ?>" placeholder="<?php echo $btn_apiKey_placeholder; ?>" class="form-control">
                        <div class="text-muted"><?php echo $btn_apiKey_description; ?></div>
                    </div>
                    <div class="group">
                        <label><?php echo $btn_username_text; ?></label>
                        <input type="text" name="wappipro_username" value="<?php echo $wappipro_username; ?>" placeholder="<?php echo $btn_username_placeholder; ?>" class="form-control">
                        <div class="text-muted"><?php echo $btn_username_description; ?></div>
                    </div>
                    <div class="group">
                        <label><?php echo $btn_test_text; ?></label>
                        <input type="text" name="wappipro_test_phone_number" value="<?php echo $wappipro_test_phone_number; ?>" placeholder="<?php echo $btn_test_placeholder; ?>" class="form-control">
                        <div class="text-muted"><?php echo $btn_test_description; ?></div>
                        <input type="submit" name="wappipro_test" value="<?php echo $btn_test_send; ?>" class="btn btn-primary">
                    </div>
                    <?php foreach ($order_status_list as $order_status) { ?>
                        <div class="group">
                            <label><?php echo $order_status['name']; ?></label>
                            <input type="checkbox" name="wappipro_<?php echo $order_status['order_status_id']; ?>_active" value="true" <?php echo ($wappipro_order_status_active[$order_status['order_status_id']] == 'true' ? 'checked="checked"' : ''); ?>>
                            <label><?php echo $btn_duble_admin; ?></label>
                            <input type="checkbox" name="wappipro_admin_<?php echo $order_status['order_status_id']; ?>_active" value="true" <?php echo ($wappipro_admin_order_status_active[$order_status['order_status_id']] == 'true' ? 'checked="checked"' : ''); ?>>
                            <textarea name="wappipro_<?php echo $order_status['order_status_id']; ?>_message" class="form-control"><?php echo $wappipro_order_status_message[$order_status['order_status_id']]; ?></textarea>
                            <div class="text-muted"><?php echo $btn_status_order_description; ?></div>
                        </div>
                    <?php } ?>
                    <input type="submit" name="wappipro_save_settings" value="<?php echo $btn_token_save_all; ?>">
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>
