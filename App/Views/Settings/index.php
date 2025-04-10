<div class="row">
    <div class="col-sm-12">
        <div class="home-tab">
            <div class="card card-rounded mt-3">
                <div class="card-body">
                    <h4 class="card-title">Settings</h4>

                    <?php if (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>

                    <form id="settings-form" class="forms-sample" method="POST" action="<?php echo INSTALL_URL; ?>?controller=Settings&action=index">

                        <div class="row">
                            <?php
                            $counter = 0;
                            foreach ($tpl['settings'] as $setting):
                                // Skip Timezone
                                if ($setting['key'] === 'timezone') continue;

                                // Start a new row for every two settings
                                if ($counter % 2 === 0): ?>
                                    </div><div class="row">
                                <?php endif; ?>

                                <div class="form-group col-md-6 mb-3">
                                    <label for="<?php echo $setting['key']; ?>" class="form-label"><?php echo ucwords(str_replace('_', ' ', $setting['key'])); ?></label>
                                    <?php if ($setting['key'] === 'email_sending'): ?>
                                        <select class="form-control settings-input" id="<?php echo $setting['key']; ?>" name="settings[<?php echo $setting['key']; ?>]" required>
                                            <option value="enabled" <?php echo ($setting['value'] == 'enabled') ? 'selected' : ''; ?>
                                                <?php echo (!MAIL_CONFIGURED) ? 'disabled' : ''; ?>>Enabled</option>
                                            <option value="disabled" <?php echo ($setting['value'] == 'disabled') ? 'selected' : ''; ?>>Disabled</option>
                                        </select>
                                        <?php if (!MAIL_CONFIGURED): ?>
                                            <a href="<?php echo INSTALL_URL; ?>?controller=Install&action=step4" class="btn btn-warning mt-2">Configure Email</a>
                                        <?php endif; ?>
                                    <?php elseif ($setting['key'] === 'date_format'): ?>
                                        <select class="form-control settings-input" id="date_format" name="settings[date_format]" required>
                                            <?php
                                            foreach (Utility::$dateFormats as $format => $label) {
                                                $selected = ($format == $setting['value']) ? 'selected' : '';
                                                echo "<option value=\"{$format}\" $selected>{$label}</option>";
                                            }
                                            ?>
                                        </select>
                                    <?php elseif ($setting['key'] === 'opening_time' || $setting['key'] === 'closing_time' || $setting['key'] === 'weekend_opening_time' || $setting['key'] === 'weekend_closing_time' || $setting['key'] === 'order_cut_off_time'): ?>
                                        <input type="time" class="form-control settings-input" id="<?php echo $setting['key']; ?>" name="settings[<?php echo $setting['key']; ?>]" value="<?php echo $setting['value']; ?>" required>
                                    <?php elseif ($setting['key'] === 'weekend_operation'): ?>
                                        <select class="form-control settings-input" id="<?php echo $setting['key']; ?>" name="settings[<?php echo $setting['key']; ?>]" required>
                                            <option value="1" <?php echo ($setting['value'] == '1') ? 'selected' : ''; ?>>Enabled</option>
                                            <option value="0" <?php echo ($setting['value'] == '0') ? 'selected' : ''; ?>>Disabled</option>
                                        </select>
                                    <?php elseif ($setting['key'] === 'default_order_status'): ?>
                                        <input type="text" class="form-control settings-input" id="<?php echo $setting['key']; ?>" name="settings[<?php echo $setting['key']; ?>]" value="<?php echo $setting['value']; ?>" required>
                                    <?php /* ?>
                                    <?php elseif ($setting['key'] === 'tax_rate'): ?>
                                        <input type="number" step="0.01" min="0" class="form-control settings-input" id="<?php echo $setting['key']; ?>" name="settings[<?php echo $setting['key']; ?>]" value="<?php echo $setting['value']; ?>" required>
                                    <?php elseif ($setting['key'] === 'shipping_rate'): ?>
                                        <input type="number" step="0.01" min="0" class="form-control settings-input" id="<?php echo $setting['key']; ?>" name="settings[<?php echo $setting['key']; ?>]" value="<?php echo $setting['value']; ?>" required>
                                    <?php elseif ($setting['key'] === 'currency_code'): ?>
                                        <select class="form-control settings-input" id="<?php echo $setting['key']; ?>" name="settings[<?php echo $setting['key']; ?>]" required>
                                        <?php
                                        foreach (Utility::$currencies as $k => $v) {
                                            $selected = ($k == $setting['value']) ? 'selected' : '';
                                            echo "<option value=\"{$k}\" $selected>{$v}</option>";
                                        }
                                        ?>
                                        </select>
                                    <?php */ ?>
                                    <?php endif; ?>
                                </div>
                                <?php $counter++; ?>
                            <?php endforeach; ?>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" id="save-btn" class="btn btn-primary text-white me-2" disabled>Save Changes</button>
                                <button type="button" id="undo-btn" class="btn btn-outline-dark" disabled>Undo</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>