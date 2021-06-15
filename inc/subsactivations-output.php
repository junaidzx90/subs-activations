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
        $has_sub = wcs_user_has_subscription( '', '', 'active' );
        if($has_sub):
        ?>
            <form action="/" method="post" id="subsactivations">
                <div class="ufields">
                    <?php
                    $order = wc_get_order( has_active_subscription()[0] );
                    $items = $order->get_items();
                    $product_name = '';
                    foreach ( $items as $item ) {
                        $product_name = $item->get_name();
                        $product_id = $item->get_product_id();
                    }
                    echo '<h5 style="margin-top:0px" class="product_mtids_ttl">('.$product_id.') '.ucfirst($product_name).'</h5>';
                    
                    $table = $wpdb->prefix.'subsactivations__v2';
                    $account1 = $wpdb->get_var("SELECT account_number FROM $table WHERE user_id = $current_user->ID AND pos = 1");
                    $account2 = $wpdb->get_var("SELECT account_number FROM $table WHERE user_id = $current_user->ID AND pos = 2");
                    ?>

                    <label for="ac_number_1">Account Number 1</label>
                    <input type="number" id="ac_number_1" name="ac_number_1" placeholder="Account Number 1" value="<?php echo ((!empty($account1) || $account1 != 0)? __($account1, 'field-form') : ''); ?>" <?php echo (empty($account1)? 'required' : ''); ?> >

                    <label for="ac_number_2">Account Number 2 </label>
                    <input type="number" id="ac_number_2" name="ac_number_2" placeholder="Account Number 2" value="<?php echo ((!empty($account2) || $account2 != 0)? __($account2, 'field-form') : ''); ?>">

                    <input type="submit" name="activate" id="subsactivations-subtn" value="<?php echo (get_option( 'subsactivations_section_activate_btn', '' ) ? get_option( 'subsactivations_section_activate_btn', '' ):'Activate'); ?>">
                <?php

                // product_id_
                global $wpdb,$current_user;
                $products_info = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}lic_produce_info WHERE product_id > 0");

                if(!empty($products_info)){
                    $i = 1;
                    foreach($products_info as $product){
                        $product_id = $product->product_id;
                        $of_license = $product->of_License;

                        if($wpdb->get_var("SELECT ID FROM {$wpdb->prefix}lic_activations WHERE Userid = {$current_user->ID} AND Product_id = $product_id")){

                            echo '<h5 class="product_mtids_ttl">('.$product_id.') '.ucfirst(get_post($product_id)->post_title).'</h5>';
                            
                            for($x = 0; $x < $of_license;$x++){
                                $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}lic_activations WHERE Userid = {$current_user->ID} AND Product_id = $product_id");
                                $data = [
                                    'name' => 'mtid_'.$x,
                                    'placeholder' => 'Account number',
                                    'value' => !empty($results[$x]->Mtid)?$results[$x]->Mtid:'',
                                    'product_id'    => $product_id,
                                    'id'    => !empty($results[$x]->ID)?$results[$x]->ID:'',
                                ];
                                echo mtids_inputs($data);
                            }
                        }
                        $i++;
                    }
                    if($wpdb->get_var("SELECT ID FROM {$wpdb->prefix}lic_activations WHERE Userid = {$current_user->ID}")){
                        echo '<input type="submit" name="mtids_activate" id="subsactivations-mtidsbtn" value="'.(get_option( 'subsactivations_section_activate_btn', '' ) ? get_option( 'subsactivations_section_activate_btn', '' ):'Activate').'">';
                    }
                }
                ?>

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