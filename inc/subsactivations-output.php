<?php
function subsactivations_output($atts){
    ob_start();
    ?>
    <form action="/" method="post" id="subsactivations">
        <div class="ufields" <?php echo (get_option( 'form_subsactivations__bg') == 'checked'? 'style="background: #fff;"': ''); ?>>
            <?php
            global $wpdb,$current_user;
            $table = $wpdb->prefix.'subsactivations__v1';
            $data = $wpdb->get_row("SELECT * FROM $table WHERE user_id = $current_user->ID");
            ?>

            <label for="ac_number_1">Account Number 1</label>
            <input type="number" id="ac_number_1" name="ac_number_1" placeholder="Account Number 1" value="<?php echo ((!empty($data->account1) || $data->account1 != 0)? __($data->account1, 'field-form') : ''); ?>" <?php echo (empty($data->account1)? 'required' : 'readonly'); ?>>

            <label for="ac_number_2">Account Number 2 <span class="optional">(Optional)</span></label>
            <input type="number" id="ac_number_2" name="ac_number_2" placeholder="Account Number 2" value="<?php echo ((!empty($data->account2) || $data->account2 != 0)? __($data->account2, 'field-form') : ''); ?>">

            <input type="submit" name="activate" id="subsactivations-subtn" value="Activate">
        </div>
    </form>

    <?php
    return ob_get_clean();
}