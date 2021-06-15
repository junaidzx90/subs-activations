<?php
 /**
 *
 * @link              https://github.com/mql4Expert/checkout_activation
 * @since             1.0.0
 * @package           subsactivations
 *
 * @wordpress-plugin
 * Plugin Name:       Activations
 * Plugin URI:        https://github.com/activations
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Mql4Expert
 * Author URI:        https://github.com/mql4Expert/about
 * Text Domain:       subsactivations
 * Domain Path:       /languages
 */

define( 'SUBSACT_NAME', 'subsactivations' );
define( 'SUBSACT_PATH', plugin_dir_path( __FILE__ ) );
define( 'SUBSACT_URL', plugin_dir_url( __FILE__ ) );
register_activation_hook( __FILE__, 'activate_subsactivations_cplgn' );
register_deactivation_hook( __FILE__, 'deactivate_subsactivations_cplgn' );
require_once plugin_dir_path( __FILE__ )."inc/activation_activate.php";
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) && ! defined( 'SUBSACT_NAME' ) && ! defined( 'SUBSACT_PATH' ) ) {
	die;
}

function subsactivations_admin_noticess(){
    $message = sprintf(
        /* translators: 1: Plugin Name 2: Elementor */
        esc_html__( '%1$s requires %2$s to be installed and activated.', 'subsactivations' ),
        '<strong>' . esc_html__( 'Activations', 'subsactivations' ) . '</strong>',
        '<strong>' . esc_html__( 'Woocommerce Subscriptions', 'subsactivations' ) . '</strong>'
    );

    printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
}

function subsactivations_dependency() {
    if(!class_exists('WC_Subscriptions')){
        add_action( 'admin_notices', 'subsactivations_admin_noticess' );
    }
    load_plugin_textdomain( 'subsactivations', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

// Update lic_activation inputs from admin menu
function activations_update_lic_activations_inputs(){
    if(isset($_POST['inputs_val'])){
        global $wpdb;

        if(isset($_POST['product_id-0']) || isset($_POST['of_license-0']) || isset($_POST['product_code-0']) || isset($_POST['comment-0']) || isset($_POST['id-0'])){
            $id_0 = intval($_POST['id-0']);
            $product_id_0 = intval($_POST['product_id-0']);
            $of_license_0 = intval($_POST['of_license-0']);
            $product_code_0 = sanitize_text_field($_POST['product_code-0']);
            $comment_0 = sanitize_text_field($_POST['comment-0']);

            if(!$wpdb->get_var("SELECT ID FROM {$wpdb->prefix}lic_produce_info WHERE ID = $id_0")){
                $wpdb->insert($wpdb->prefix.'lic_produce_info',
                    array(
                        'product_id' => $product_id_0,
                        'of_License' => $of_license_0,
                        'product_code' => $product_code_0,
                        'comment' => $comment_0,
                    ),array('%d','%d','%s','%s'));
            }else{
                $wpdb->update($wpdb->prefix.'lic_produce_info',
                    array(
                        'product_id' => $product_id_0,
                        'of_License' => $of_license_0,
                        'product_code' => $product_code_0,
                        'comment' => $comment_0,
                    ),array('ID' => $id_0),array('%d','%d','%s','%s'),array('%d'));
            }
        }

        if(isset($_POST['product_id-1']) || isset($_POST['of_license-1']) || isset($_POST['product_code-1']) || isset($_POST['comment-1']) || isset($_POST['id-1'])){
            $id_1 = intval($_POST['id-1']);
            $product_id_1 = intval($_POST['product_id-1']);
            $of_license_1 = intval($_POST['of_license-1']);
            $product_code_1 = sanitize_text_field($_POST['product_code-1']);
            $comment_1 = sanitize_text_field($_POST['comment-1']);

            if(!$wpdb->get_var("SELECT ID FROM {$wpdb->prefix}lic_produce_info WHERE ID = $id_1")){
                $wpdb->insert($wpdb->prefix.'lic_produce_info',
                    array(
                        'product_id' => $product_id_1,
                        'of_License' => $of_license_1,
                        'product_code' => $product_code_1,
                        'comment' => $comment_1,
                    ),array('%d','%d','%s','%s'));
            }else{
                $wpdb->update($wpdb->prefix.'lic_produce_info',
                    array(
                        'product_id' => $product_id_1,
                        'of_License' => $of_license_1,
                        'product_code' => $product_code_1,
                        'comment' => $comment_1,
                    ),array('ID' => $id_1),array('%d','%d','%s','%s'),array('%d'));
            }
        }
        if(isset($_POST['product_id-2']) || isset($_POST['of_license-2']) || isset($_POST['product_code-2']) || isset($_POST['comment-2']) || isset($_POST['id-2'])){
            $id_2 = intval($_POST['id-2']);
            $product_id_2 = intval($_POST['product_id-2']);
            $of_license_2 = intval($_POST['of_license-2']);
            $product_code_2 = sanitize_text_field($_POST['product_code-2']);
            $comment_2 = sanitize_text_field($_POST['comment-2']);

            if(!$wpdb->get_var("SELECT ID FROM {$wpdb->prefix}lic_produce_info WHERE ID = $id_2")){
                $wpdb->insert($wpdb->prefix.'lic_produce_info',
                    array(
                        'product_id' => $product_id_2,
                        'of_License' => $of_license_2,
                        'product_code' => $product_code_2,
                        'comment' => $comment_2,
                    ),array('%d','%d','%s','%s'));
            }else{
                $wpdb->update($wpdb->prefix.'lic_produce_info',
                    array(
                        'product_id' => $product_id_2,
                        'of_License' => $of_license_2,
                        'product_code' => $product_code_2,
                        'comment' => $comment_2,
                    ),array('ID' => $id_2),array('%d','%d','%s','%s'),array('%d'));
            }
        }
        if(isset($_POST['product_id-3']) || isset($_POST['of_license-3']) || isset($_POST['product_code-3']) || isset($_POST['comment-3']) || isset($_POST['id-3'])){
            $id_3 = intval($_POST['id-3']);
            $product_id_3 = intval($_POST['product_id-3']);
            $of_license_3 = intval($_POST['of_license-3']);
            $product_code_3 = sanitize_text_field($_POST['product_code-3']);
            $comment_3 = sanitize_text_field($_POST['comment-3']);

            if(!$wpdb->get_var("SELECT ID FROM {$wpdb->prefix}lic_produce_info WHERE ID = $id_3")){
                $wpdb->insert($wpdb->prefix.'lic_produce_info',
                    array(
                        'product_id' => $product_id_3,
                        'of_License' => $of_license_3,
                        'product_code' => $product_code_3,
                        'comment' => $comment_3,
                    ),array('%d','%d','%s','%s'));
            }else{
                $wpdb->update($wpdb->prefix.'lic_produce_info',
                    array(
                        'product_id' => $product_id_3,
                        'of_License' => $of_license_3,
                        'product_code' => $product_code_3,
                        'comment' => $comment_3,
                    ),array('ID' => $id_3),array('%d','%d','%s','%s'),array('%d'));
            }
        }
    }
}

require_once plugin_dir_path( __FILE__ )."inc/activation_action_hooks.php";
require_once plugin_dir_path( __FILE__ )."inc/activation_menus.php";
require_once plugin_dir_path( __FILE__ )."inc/activation_scripts.php";
require_once plugin_dir_path( __FILE__ )."inc/activation_menu_items.php";
require_once plugin_dir_path( __FILE__ )."inc/activation_myaccount_menus.php";

function my_column_init(){
    add_filter('manage_edit-shop_subscription_columns', 'wp_shop_subscription_list_table_columnname');
    add_filter('manage_edit-shop_order_columns', 'wp_shop_orders_list_table_columnname');
    add_action('manage_shop_order_posts_custom_column','wp_wc_order_column_view');
    add_action('manage_shop_subscription_posts_custom_column','wp_wc_subscription_column_view');
}

// Dectivision function
function deactivate_subsactivations_cplgn(){
    // Nothing For Now
}

// Delete all colors
function subsactivations_reset_colors(){
    delete_option( 'subsactivations_activate_button' );
    delete_option( 'subsactivations_purchase_button' );
    delete_option( 'subsactivations_txt_color' );
    delete_option( 'subsactivations_notification_color' );
    delete_option('subsactivations_header_color');
    echo 'Success';
    wp_die();
}

// Menu callback funnction
function subsactivations_menupage_display(){
    if(class_exists('WC_Subscriptions')){
        require_once plugin_dir_path( __FILE__ )."inc/subsactivation-admin-view.php";
    }
}


require_once 'inc/subsactivations-output.php';

function activations_post_show(){
    ob_start();
    global $current_user;
    $form_accessed = false;
    if(class_exists('WC_Subscriptions')){
        $users_subscriptions = wcs_get_users_subscriptions($current_user->ID);
        foreach ($users_subscriptions as $subscription){
            if ($subscription->has_status(array('active'))) {
                $form_accessed = true;
            }
        }
    }
    $product_a = get_option( 'subsactivations_product_a', 0 );
    $product_b = get_option( 'subsactivations_product_b', 0 );
    $product_c = get_option( 'subsactivations_product_c', 0 );

    if(has_bought_items($current_user->ID, $product_a) || has_bought_items($current_user->ID, $product_b) || has_bought_items($current_user->ID, $product_c) || $form_accessed == true){
        ?>
        <div class="requires">
        <?php
        $post_id = get_option( 'subsactivations_post_id', 1765 );
        $post = get_post($post_id);
        if($post){
            echo '<p>'.$post->post_content.'</p>';
        }
        ?>
        </div>
        <?php
        $output = ob_get_contents();
        ob_get_clean();
        return $output;
    }else{
        // Colors Include
        require_once SUBSACT_PATH.'inc/subsactivations-colors.php';
        $url = get_option( 'subsactivations_url', '' );
        ?>
        <div class="purchase_btnwrap">
            <a id="purchase-btn" href="<?php echo esc_url($url); ?>"> Please start the subscription </a>
        </div>
        <?php
        $output = ob_get_contents();
        ob_get_clean();
        return $output;
    }
}

// Input component for product mtid
function mtids_inputs($data = array()){
    $output = '';
    $output .= '<input data-id="'.$data['id'].'" class="mtids" type="number" p-id="'.$data['product_id'].'" placeholder="'.$data['placeholder'].'" value="'.$data['value'].'" name="'.$data['name'].'">';
    return $output;
}

// Store mtids
function subsactivations_mtids_store(){
    if(!wp_verify_nonce( $_POST['nonce'], 'nonces' )){
        die();
    }
    if(isset($_POST['data'])){
        $datas = $_POST['data'];
        foreach($datas as $data){
            $product_id = intval($data['product_id']);
            $values = intval($data['values']);
            $dataid = intval($data['id']);
            global $wpdb,$current_user;

            $order_id = get_orders_ids_by_product_id($product_id);
            $order = wc_get_order( $order_id );
            $subscriptions = wcs_get_subscriptions_for_order($order, array('order_type' => 'parent'));
            $is_subscription = false;
            if(!empty($subscriptions)){
                $is_subscription = true;
            }

            $product_code = $wpdb->get_var("SELECT product_code FROM {$wpdb->prefix}lic_produce_info WHERE product_id = $product_id");
                
            if($entryID = $wpdb->get_var("SELECT ID FROM {$wpdb->prefix}lic_activations WHERE Product_id = $product_id AND Userid = $current_user->ID AND ID = $dataid")){
                $wpdb->update($wpdb->prefix.'lic_activations',array(
                    'Product_id' => $product_id,
                    'Mtid' => $values,
                    'Prodcode' => $product_code, 
                    'Editable' => $is_subscription
                ),array('ID' => $entryID),array('%d','%d','%s','%d'),array('%d'));
            }else{
                $insert = $wpdb->insert($wpdb->prefix.'lic_activations', array(
                    'Product_id' => $product_id,
                    'Userid' => $current_user->ID,
                    'Mtid' => $values,
                    'Prodcode' => $product_code, 
                    'Editable' => $is_subscription,
                    'Status' => 1,
                    'UserName' => $current_user->display_name
                ),array('%d','%d','%d','%s','%d','%s'));
            }
        }
        die; 
    }
    die;
}


/**
 * { AJAX CALLING FOR INSERTING AND UPDATING }
 */
function subsactivations_data_check(){
    if(wp_verify_nonce( $_POST['nonce'], 'nonces' )){
        global $wpdb,$current_user;
        $number_1 = intval($_POST['number_1']);
        $number_2 = intval($_POST['number_2']);
        
        $table = $wpdb->prefix.'subsactivations__v2';
        $account1 = $wpdb->get_row("SELECT * FROM $table WHERE user_id = $current_user->ID AND pos = 1");
        $account2 = $wpdb->get_row("SELECT * FROM $table WHERE user_id = $current_user->ID AND pos = 2");

        //For email
        $admin_email = get_option( 'admin_email' );
        $user_email = $current_user->user_email;
        $subject = 'FP-('.$current_user->ID.')-('.$current_user->display_name.')';

        // Update account 1
        if($account1->account_number){
            $wpdb->update($table, array(
                'account_number' => $number_1,
                'username' => $current_user->display_name 
            ),array(
                "user_id" => $current_user->ID,
                "pos" => 1,
            ),array('%d','%s'),array('%d','%d'));
        }

        // Update acccount 2
        if($account2->account_number){
            $wpdb->update($table, array(
                'account_number' => $number_2,
                'username' => $current_user->display_name 
            ),array(
                "user_id" => $current_user->ID,
                "pos" => 2,
            ),array('%d','%s'),array('%d','%d'));
        }

        // Insert account 1
        if(!$account1->account_number){
            $wpdb->insert($table, array(
                'user_id' => $current_user->ID,
                'account_number' => $number_1,
                'username' => $current_user->display_name,
                "pos" => 1,
            ),array('%d','%d','%s','%d'));
        }

        // Insert account 2
        if(!$account2->account_number){
            $wpdb->insert($table, array(
                'user_id' => $current_user->ID,
                'account_number' => $number_2,
                'username' => $current_user->display_name,
                "pos" => 2,
            ),array('%d','%d','%s','%d'));
        }
  
        // Manage Notifications & Messages
        if ( !is_wp_error( $wpdb ) ) {
            if(!empty($number_2) && intval($account2->account_number) == $number_2 && empty($number_1)){
                echo wp_json_encode(array('error' => 'Already Exist!'));
                wp_die();
            }

            if(!empty($number_2) && intval($account2->account_number) == $number_2 && !empty($number_1) && intval($account1->account_number) == $number_1){
                echo wp_json_encode(array('error' => 'Already Exist!'));
                wp_die();
            }
            if(!empty($number_1) && intval($account1->account_number) == $number_1 && empty($number_2)){
                echo wp_json_encode(array('error' => 'Already Exist!'));
                wp_die();
            }

            if(!empty($number_1) && intval($account1->account_number) == $number_1 && !empty($number_2) && intval($account2->account_number) == $number_2){
                echo wp_json_encode(array('error' => 'Already Exist!'));
                wp_die();
            }

            if(!empty($number_1) && intval($account1->account_number) !== $number_1 && !empty($number_2) && intval($account2->account_number) == $number_2){
                echo wp_json_encode(array('changed' => 'Account license changed from <span class="number">#'. intval($account1->account_number) .'</span> to  <span class="number">#'. $number_1.'</span>'));
                
                // Send to admin
                if(!current_user_can( 'administrator' )){
                    $message = $user_email. ' Changed license from '.intval($account1->account_number).' to '.$number_1;
                    wp_mail($admin_email, $subject, $message);
                }
    
                wp_die();
            }
    
            if(!empty($number_1) && intval($account1->account_number) !== $number_1 && !empty($number_2) && intval($account2->account2) !== $number_2){
                echo wp_json_encode(array('changedboth' => 'Account license changed from <span class="number">#'. intval($account1->account_number) .'</span> to <span class="number">#'. $number_1 .'</span>', 'successboth' => 'Account <span class="number"> #'.$number_2.' </span> is activated.'));
    
                // Send to admin
                if(!current_user_can( 'administrator' )){
                    $message = $user_email. " Changed license from #".intval($account1->account_number)." to #".$number_1.".\n#".$number_2." accounts activated.";
                    wp_mail($admin_email, $subject, $message);
                }
                
                wp_die();
            }
    
            if(!empty($number_2) && intval($account2->account_number) !== $number_2 && !empty($number_1) && intval($account1->account_number) == $number_1){
                echo wp_json_encode(array('success' => 'Account <span class="number"> #'.$number_2.' </span> is activated.' ));
    
                // Send to admin
                if(!current_user_can( 'administrator' )){
                    $message = $user_email.' Activated #'. $number_2.' account.';
                    wp_mail($admin_email, $subject, $message);
                }
    
                wp_die();
            }
    
            if(empty($number_2) && !empty($number_1)){
                echo wp_json_encode(array('success' => 'Account <span class="number"> #'.$number_1.' </span> is activated.' ));
    
                // Send to admin
                if(!current_user_can( 'administrator' )){
                    $message = $user_email.' activated #'. $number_1.' account.';
                    wp_mail($admin_email,$subject,$message);
                }
            }else{
                // Send to admin
                if(!current_user_can( 'administrator' )){
                    $message = $user_email.' activated #'. $number_2.' account.';
                    wp_mail($admin_email, $subject, $message);
                }
                echo wp_json_encode(array('success' => 'Account <span class="number"> '.$number_2.' </span>is activated.'));
            }
        }else{
            echo wp_json_encode(array('error' => '🙄Error. Try again!'));
            wp_die();
        }
        wp_die();
    }
    wp_die();
}

// Has bought products
function has_bought_items( $user_var = 0,  $product_ids = 0 ) {
    global $wpdb;
    
    // Based on user ID (registered users)
    if ( is_numeric( $user_var) ) { 
        $meta_key     = '_customer_user';
        $meta_value   = $user_var == 0 ? (int) get_current_user_id() : (int) $user_var;
    } 
    // Based on billing email (Guest users)
    else { 
        $meta_key     = '_billing_email';
        $meta_value   = sanitize_email( $user_var );
    }
    
    $paid_statuses    = array_map( 'esc_sql', wc_get_is_paid_statuses() );
    $product_ids      = is_array( $product_ids ) ? implode(',', $product_ids) : $product_ids;

    $line_meta_value  = $product_ids !=  ( 0 || '' ) ? 'AND woim.meta_value IN ('.$product_ids.')' : 'AND woim.meta_value != 0';

    // Count the number of products
    $count = $wpdb->get_var( "
        SELECT COUNT(p.ID) FROM {$wpdb->prefix}posts AS p
        INNER JOIN {$wpdb->prefix}postmeta AS pm ON p.ID = pm.post_id
        INNER JOIN {$wpdb->prefix}woocommerce_order_items AS woi ON p.ID = woi.order_id
        INNER JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS woim ON woi.order_item_id = woim.order_item_id
        WHERE p.post_status IN ( 'wc-" . implode( "','wc-", $paid_statuses ) . "' )
        AND pm.meta_key = '$meta_key'
        AND pm.meta_value = '$meta_value'
        AND woim.meta_key IN ( '_product_id', '_variation_id' ) $line_meta_value 
    " );

    if($product_ids == ''){
        return 0;
    }else{
        // Return true if count is higher than 0 (or false)
        return $count; 
    }
   
}

// Get order id by product id
function get_orders_ids_by_product_id( $product_id ) {
    global $wpdb;
    
    // Define HERE the orders status to include in
    $orders_statuses = "'wc-completed', 'wc-processing', 'wc-on-hold'";

    # Get All defined statuses Orders IDs for a defined product ID (or variation ID)
    return $wpdb->get_var( "
        SELECT DISTINCT woi.order_id
        FROM {$wpdb->prefix}woocommerce_order_itemmeta as woim, 
             {$wpdb->prefix}woocommerce_order_items as woi, 
             {$wpdb->prefix}posts as p
        WHERE  woi.order_item_id = woim.order_item_id
        AND woi.order_id = p.ID
        AND p.post_status IN ( $orders_statuses )
        AND woim.meta_key IN ( '_product_id', '_variation_id' )
        AND woim.meta_value LIKE '$product_id'
        ORDER BY woi.order_item_id DESC"
    );
}


function has_active_subscription( $user_id=null ) {
    // When a $user_id is not specified, get the current user Id
    if( null == $user_id && is_user_logged_in() ) 
        $user_id = get_current_user_id();
    // User not logged in we return false
    if( $user_id == 0 ) 
        return false;

    // Get all active subscriptions for a user ID
    $active_subscriptions = get_posts( array(
        'numberposts' => 1, // Only one is enough
        'meta_key'    => '_customer_user',
        'meta_value'  => $user_id,
        'post_type'   => 'shop_subscription', // Subscription post type
        'post_status' => 'wc-active', // Active subscription
        'fields'      => 'ids', // return only IDs (instead of complete post objects)
    ) );

    return $active_subscriptions;
}
/**
 * When user buy defined products
 */
add_action( 'woocommerce_checkout_order_created', 'moresell_order_processing', 10, 1);
function moresell_order_processing($order){
    global $wpdb,$current_user;
    $items = $order->get_items();
    $order_id = $order->get_ID();

    $subscriptions = wcs_get_subscriptions_for_order($order, array('order_type' => 'parent'));
    $is_subscription = false;
    if(!empty($subscriptions)){
        $is_subscription = true;
    }

    $product_id = 0;
    foreach ( $items as $item ) {
        $product_id = $item->get_product_id();
    }

    $date = date("Y-m-d h:i:sa");
    $expiration_date = WC_Subscriptions_Product::get_expiration_date($product_id);
    // Product code
    $product_code = $wpdb->get_var("SELECT product_code FROM {$wpdb->prefix}lic_produce_info WHERE product_id = $product_id");
    // Expected inputs
    $oflocense = 0;
    if($oflocense = $wpdb->get_var("SELECT of_License FROM {$wpdb->prefix}lic_produce_info WHERE product_id = $product_id")){
        $oflocense = $oflocense;
    }
    // Already inserted
    $inserted = 0;
    if($inserted = $wpdb->query("SELECT * FROM {$wpdb->prefix}lic_activations WHERE Product_id = $product_id AND Userid = {$current_user->ID}")){
        $inserted = $inserted;
    }
    // It's for hard modify (Not usable->It can hit work for test by admmin)
    if($inserted>0){
        $wpdb->query("DELETE FROM {$wpdb->prefix}lic_activations WHERE `Product_id` = $product_id AND Userid = {$current_user->ID}");
        $inserted = 0;
    }

    if($oflocense){
        for($i = $inserted; $i < $oflocense;$i++){
            $wpdb->insert($wpdb->prefix.'lic_activations',array(
                'Orderno' => $order_id,
                'Userid' => $current_user->ID,
                'Editable' => $is_subscription,
                'Status' => 1,
                'Product_id' => $product_id,
                'UserName' => $current_user->display_name,
                'Prodcode' => ($product_code?$product_code:''),
                'Modifydate' => $date,
                'Expirytime' => ($expiration_date?$expiration_date:''),
            ),array('%d','%d','%d','%s','%s','%s','%s'));
        }
    }
}
/**
 * Column lists for subscription table
 */
function wp_shop_subscription_list_table_columnname($defaults)
{
    unset($defaults['orders']);
    unset($defaults['end_date']);
    unset($defaults['last_payment_date']);
    unset($defaults['next_payment_date']);
    unset($defaults['trial_end_date']);
    unset($defaults['start_date']);
    unset($defaults['recurring_total']);
    unset($defaults['order_title']);
    unset($defaults['order_items']);

    if(get_option( 'subsactivations_hide_subscription', '' ) == 'checked'){
        $defaults['activations'] = 'Activations';
    }
    if(get_option( 'subsactivations_hide_item', '' ) == 'checked'){
        $defaults['order_items'] = "Items";
    }

    $defaults['recurring_total'] = "Total";
    $defaults['order_title'] = "Subscriptions";
    $defaults['start_date'] = "Start Date";
    $defaults['trial_end_date'] = "Trial End";
    $defaults['next_payment_date']  = "Next Pay";
    $defaults['last_payment_date'] = "Last order Date";
    $defaults['end_date'] = "End Date";
    $defaults['orders'] = "Orders";
    return $defaults;
}

/**
 * Column lists for order table
 */
function wp_shop_orders_list_table_columnname($defaults)
{
    unset($defaults['order_date']);
    unset($defaults['order_status']);
    unset($defaults['subscription_relationship']);
    unset($defaults['order_total']);

    if(get_option( 'subsactivations_hide_order', '' ) == 'checked'){
        $defaults['activations'] = 'Activations';
    }

    $defaults['order_date'] = "Date";
    $defaults['order_status'] = "Status";
    $defaults['subscription_relationship'] = '<span class="subscription_head tips">Subscription Relationship</span>';
    $defaults['order_total'] = "Total";
    return $defaults;
}

// Get activations Numbers
function get_activations_user_data($user_id){
    global $wpdb;
    $userdata = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}subsactivations__v2 WHERE user_id = $user_id");
    $numbers = '';
    if($userdata){
        if(!empty($userdata->account1)){
            $numbers = '<span>'.$userdata->account1.'</span>';
        }
        if(!empty($userdata->account2)){
            $numbers .= '<span> / '.$userdata->account2.'</span>';
        }
    }
    return $numbers;
}

// Order table column data
function wp_wc_order_column_view($column_name)
{
    if ($column_name == 'activations') {
        global $the_order;
        $user_id = $the_order->get_customer_id();
        echo get_activations_user_data($user_id);
    }
}

//Subscription table column data
function wp_wc_subscription_column_view($column_name)
{
    if ($column_name == 'activations') {
        global $the_subscription;
        $user_id = $the_subscription->get_customer_id();
        echo get_activations_user_data($user_id);
    }
}