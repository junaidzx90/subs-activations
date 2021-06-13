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
                    $table = $wpdb->prefix.'subsactivations__v2';
                    $account1 = $wpdb->get_var("SELECT account_number FROM $table WHERE user_id = $current_user->ID AND pos = 1");
                    $account2 = $wpdb->get_var("SELECT account_number FROM $table WHERE user_id = $current_user->ID AND pos = 2");
                    ?>

                    <label for="ac_number_1">Account Number 1</label>
                    <input type="number" id="ac_number_1" name="ac_number_1" placeholder="Account Number 1" value="<?php echo ((!empty($account1) || $account1 != 0)? __($account1, 'field-form') : ''); ?>" <?php echo (empty($account1)? 'required' : ''); ?> >

                    <label for="ac_number_2">Account Number 2 <span class="optional">(Optional)</span></label>
                    <input type="number" id="ac_number_2" name="ac_number_2" placeholder="Account Number 2" value="<?php echo ((!empty($account2) || $account2 != 0)? __($account2, 'field-form') : ''); ?>">

                    <input type="submit" name="activate" id="subsactivations-subtn" value="<?php echo (get_option( 'subsactivations_section_activate_btn', '' ) ? get_option( 'subsactivations_section_activate_btn', '' ):'Activate'); ?>">

                <?php

                    // $product_a = get_option( 'subsactivations_product_a', 0 );
                    // $product_b = get_option( 'subsactivations_product_b', 0 );
                    // $product_c = get_option( 'subsactivations_product_c', 0 );

                    // if(has_bought_items($current_user->ID, $product_a) || has_bought_items($current_user->ID, $product_b) || has_bought_items($current_user->ID, $product_c)){
                    //     // Product a
                    //     if(has_bought_items($current_user->ID, $product_a)){
                    //         echo '<h5 class="product_mtids_ttl">Active your '.(has_bought_items($current_user->ID,$product_a )>1?'mtID\'s':'mtID').' For <span class="product_name">--( '.get_post($product_a)->post_title.' )--</span></h5>';
                    //         for($i = 0; $i<has_bought_items($current_user->ID,$product_a);$i++){
                    //             $mtvalue_a = $wpdb->get_var("SELECT mtid FROM {$wpdb->prefix}subsactivation_products_v2 WHERE product_id = $product_a AND pos = $i");

                    //             $data = [
                    //                 'name' => 'mtid_'.$i,
                    //                 'placeholder' => 'Mtid '.($i+1),
                    //                 'value' => ($mtvalue_a)?$mtvalue_a:'',
                    //                 'pos'   => $i,
                    //                 'product_id'    => $product_a
                    //             ];
                    //             echo mtids_inputs($data);
                    //         }
                    //     }
                    //     // Product b
                    //     if(has_bought_items($current_user->ID, $product_b)){
                    //         echo '<h5 class="product_mtids_ttl">Active your mtID\'s For <span class="product_name">--( '.get_post($product_b)->post_title.' )--</span></h5>';
                    //         $x = 2;
                    //         for($i = 0; $i<3;$i++){
                    //             $mtvalue_b = $wpdb->get_var("SELECT mtid FROM {$wpdb->prefix}subsactivation_products_v2 WHERE product_id = $product_b AND pos = $x");

                    //             $data = [
                    //                 'name' => 'mtid_'.$x,
                    //                 'placeholder' => 'Mtid '.($i+1),
                    //                 'value' => ($mtvalue_b)?$mtvalue_b:'',
                    //                 'pos'   => $x,
                    //                 'product_id'    => $product_b
                    //             ];
                    //             echo mtids_inputs($data);
                    //             $x++;
                    //         }
                    //     }
                    //     // Product c
                    //     if(has_bought_items($current_user->ID, $product_c)){
                    //         echo '<h5 class="product_mtids_ttl">Active your mtID\'s For <span class="product_name">--( '.get_post($product_c)->post_title.' )--</span></h5>';
                    //         $l = 5;
                    //         for($i = 0; $i<9;$i++){
                    //             $mtvalue_c = $wpdb->get_var("SELECT mtid FROM {$wpdb->prefix}subsactivation_products_v2 WHERE product_id = $product_c AND pos = $l");

                    //             $data = [
                    //                 'name' => 'mtid_'.$l,
                    //                 'placeholder' => 'Mtid '.($i+1),
                    //                 'value' => ($mtvalue_c)?$mtvalue_c:'',
                    //                 'pos'   => $l,
                    //                 'product_id'    => $product_c
                    //             ];
                    //             echo mtids_inputs($data);
                    //             $l++;
                    //         }
                    //     }

                    //     echo '<input type="submit" name="mtids_activate" id="subsactivations-mtidsbtn" value="'.(get_option( 'subsactivations_section_activate_btn', '' ) ? get_option( 'subsactivations_section_activate_btn', '' ):'Activate').'">';
                    // }
                    
                ?>

                <?php

                // product_id_
                global $wpdb,$current_user;
                $products = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}options WHERE option_name LIKE 'product_id_%'");
                if($products){
                    $i = 1;
                    foreach($products as $product){
                        $product_id = $product->option_value;
                        $of_license = get_option('license_'.$i);

                        if(has_bought_items($current_user->ID,$product_id)){

                            echo '<h5 class="product_mtids_ttl">('.get_orders_ids_by_product_id($product_id).'#Order) '.get_post($product_id)->post_title.'</h5>';

                            
                            for($x = 0; $x < $of_license;$x++){

                                $mtvalue = $wpdb->get_var("SELECT mtid FROM {$wpdb->prefix}subsactivation_products_v2 WHERE product_id = $product_id AND pos = $x");

                                $data = [
                                    'name' => 'mtid_'.$x,
                                    'placeholder' => 'Mtid '.($x+1),
                                    'value' => (!empty($mtvalue)?$mtvalue:''),
                                    'pos'   => $x,
                                    'product_id'    => $product_id
                                ];
                                echo mtids_inputs($data);
                            }
                        }
                        $i++;
                    }

                    echo '<input type="submit" name="mtids_activate" id="subsactivations-mtidsbtn" value="'.(get_option( 'subsactivations_section_activate_btn', '' ) ? get_option( 'subsactivations_section_activate_btn', '' ):'Activate').'">';
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