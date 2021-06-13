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
if (!isset($_SESSION)) session_start();
define( 'SUBSACT_NAME', 'subsactivations' );
define( 'SUBSACT_PATH', plugin_dir_path( __FILE__ ) );
define( 'SUBSACT_URL', plugin_dir_url( __FILE__ ) );


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
        $inputs = [];
        foreach($_POST as $key => $input){
            $inputs[$key] = $input;
        }
        array_pop($inputs);
        foreach($inputs as $key => $val){
            update_option( $key, $val);
        }
    }
}

require_once plugin_dir_path( __FILE__ )."inc/activation_action_hooks.php";
require_once plugin_dir_path( __FILE__ )."inc/activation_activate.php";
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
    $output .= '<input class="mtids" type="number" p-id="'.$data['product_id'].'" placeholder="'.$data['placeholder'].'" value="'.$data['value'].'" name="'.$data['name'].'" pos="'.$data['pos'].'">';
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
            $pos = intval($data['pos']);
            $product_id = intval($data['product_id']);
            $values = intval($data['values']);
            global $wpdb,$current_user;
            
            if($data['values'] == ""){
                global $wpdb,$current_user;
                $wpdb->query("DELETE FROM {$wpdb->prefix}subsactivation_products_v2 WHERE product_id = $product_id AND pos = $pos AND user_id = $current_user->ID");
            }else{
                if($entryID = $wpdb->get_var("SELECT ID FROM {$wpdb->prefix}subsactivation_products_v2 WHERE product_id = $product_id AND pos = $pos")){
                    $wpdb->update($wpdb->prefix.'subsactivation_products_v2',array('product_id' => $product_id,'user_id' => $current_user->ID,'mtid' => $values,'pos' => $pos),array('ID' => $entryID),array('%d','%d','%d','%d'),array('%d'));
                }else{
                    $wpdb->insert($wpdb->prefix.'subsactivation_products_v2', array('product_id' => $product_id,'user_id' => $current_user->ID,'mtid' => $values,'pos' => $pos),array('%d','%d','%d','%d'));
                }
            }
        }
        die;
    }
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

/**
 * When user buy defined products
 */
add_action( 'woocommerce_order_status_completed', 'moresell_order_processing', 10, 1);
function moresell_order_processing($order_id){
    global $wpdb,$current_user;
    $order = wc_get_order( $order_id );
    $items = $order->get_items();

    $subscriptions = wcs_get_subscriptions_for_order($order, array('order_type' => 'parent'));
    $is_subscription = false;
    if(!empty($subscriptions)){
        $is_subscription = true;
    }    

    $ordered_pid = 0;
    foreach ( $items as $item ) {
        $ordered_pid = $item->get_product_id();
    }

    $defined = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}options WHERE option_name LIKE 'product_id_%'");
    if($defined){
        $i =1;
        foreach($defined as $id){
            if(intval($id->option_value == $ordered_pid)){
                if(!$wpdb->get_var("SELECT ID FROM {$wpdb->prefix}lic_activations WHERE Orderno = $order_id AND Userid = {$current_user->ID}")){
                    $insert = $wpdb->insert($wpdb->prefix.'lic_activations',array(
                        'Orderno' => $order_id,
                        'Userid' => $current_user->ID,
                        'Editable' => $is_subscription,
                        'UserName' => $current_user->display_name,
                        'Prodcode' => get_option('product_code_'.$i)
                    ),array('$d','$d','$f','$s','$s'));
                }
            }
            $i++;
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