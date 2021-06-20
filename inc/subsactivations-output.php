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

    $hassubscription = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}lic_activations WHERE Userid = {$current_user->ID}");

    if($hassubscription){
        ?>
        <form action="/" method="post" id="subsactivations">
            <div class="ufields">
            <?php
            // product_id_
            global $wpdb,$current_user;
            $products_info = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}lic_produce_info WHERE product_id > 0");

            if(!empty($products_info)){
                $i = 1;
                foreach($products_info as $product){
                    $product_id = $product->product_id;

                    if($Orderno = $wpdb->get_var("SELECT Orderno FROM {$wpdb->prefix}lic_activations WHERE Userid = {$current_user->ID} AND Product_id = $product_id")){

                        $product_name = get_post($product_id)->post_title;

                        $product_variation = wc_get_product($product_id);
                        if($product_variation->get_type() == 'variation'){
                            $variation = new WC_Product_Variation($product_id);
                            $product_name = implode(" / ", $variation->get_variation_attributes());
                        }

                        echo '<h5 class="product_mtids_ttl">('.$Orderno.') '.ucfirst($product_name).'</h5>';

                        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}lic_activations WHERE Userid = {$current_user->ID} AND Product_id = $product_id");

                        for($x = 0; $x < count($results);$x++){
                            
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
    }else{
        ?>
        <div class="purchase_btnwrap">
            <a id="purchase-btn" href="<?php echo esc_url($url); ?>"> <?php echo get_option( 'subsactivations_purchase_txt', 'Please start the subscription' ) ?> </a>
        </div>
        <?php
    }
    return ob_get_clean();
}