<?php
function subsactivations_output($atts){
    ob_start();

    $url = '#';
    if(!empty($atts) && $atts['url']){
        $url = $atts['url'];
    }else{
        if(!empty(get_option( 'subsactivations_url', '' ))){
            $url = get_option( 'subsactivations_url', '' );
        }
    }
     
    // Colors Include
    require_once SUBSACT_PATH.'inc/subsactivations-colors.php';
    global $wpdb,$current_user;

    if(class_exists('WC_Subscriptions')){
        $users_subscriptions = wcs_get_users_subscriptions($current_user->ID);
        $form_accessed = false;
        foreach ($users_subscriptions as $subscription){
            if ($subscription->has_status(array('active'))) {
                $form_accessed = true;
            }
        }
        
        if($form_accessed == true):
        ?>
            <form action="/" method="post" id="subsactivations">
                <div class="ufields">
                <!-- TEXTS & TITLE COMES FROM CHECKOUT ACTIVATIONS PLUGIN-->
                <?php
                if(defined('CKOUT_NAME')){ ?>
                    <h3 class="section_title"><?php echo __((get_option('subsactivations_section_title')?get_option('subsactivations_section_title'):'Active your Licenses'), 'subsactivations') ?></h3>
                    <p><?php echo __(get_option('subsactivations_section_text_content'), 'subsactivations') ?></p>
                    <?php
                }
                ?>

                    <?php
                    $table = $wpdb->prefix.'subsactivations__v1';
                    $data = $wpdb->get_row("SELECT * FROM $table WHERE user_id = $current_user->ID");
                    ?>

                    <label for="ac_number_1">Account Number 1</label>
                    <input type="number" id="ac_number_1" name="ac_number_1" placeholder="Account Number 1" value="<?php echo ((!empty($data->account1) || $data->account1 != 0)? __($data->account1, 'field-form') : ''); ?>" <?php echo (empty($data->account1)? 'required' : ''); ?>>

                    <label for="ac_number_2">Account Number 2 <span class="optional">(Optional)</span></label>
                    <input type="number" id="ac_number_2" name="ac_number_2" placeholder="Account Number 2" value="<?php echo ((!empty($data->account2) || $data->account2 != 0)? __($data->account2, 'field-form') : ''); ?>">

                    <input type="submit" name="activate" id="subsactivations-subtn" value="<?php echo (get_option( 'subsactivations_section_activate_btn', '' ) ? get_option( 'subsactivations_section_activate_btn', '' ):'Activate'); ?>">
                </div>
            </form>
        <?php
        else:
            ?>
            <div class="purchase_btnwrap">
                <a id="purchase-btn" href="<?php echo esc_url($url); ?>"> Please start the subscription </a>
            </div>
            <?php
        endif;
    }else{
        print_r("Please contact to administrator!");
    }
    return ob_get_clean();
}