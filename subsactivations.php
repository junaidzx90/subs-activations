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
// Do not use slash after end
define( 'ACTIVATION_REST_URL', 'http://ealicense.com/api_direct_activation/api/api' );

define( 'REST_API_KEY', 'GJ5TY6G8IJ56HH87876JFJFT7HFFF' );
define( 'ACTIVATION_REST_CREATE', 'create.php' );
define( 'ACTIVATION_REST_UPDATE', 'update.php' );

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) && ! defined( 'SUBSACT_NAME' ) && ! defined( 'SUBSACT_PATH' ) ) {
	die;
}

function subsactivations_admin_nicess(){
    $message = sprintf(
        /* translators: 1: Plugin Name 2: Elementor */
        esc_html__( '%1$s requires %2$s to be installed and activated.', 'subsactivations' ),
        '<strong>' . esc_html__( 'Activations', 'subsactivations' ) . '</strong>',
        '<strong>' . esc_html__( 'Woocommerce Subscriptions', 'subsactivations' ) . '</strong>'
    );

    printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
}

add_action( 'plugins_loaded', 'subsactivations_dependency' );
function subsactivations_dependency() {
    if(!class_exists('WC_Subscriptions')){
        add_action( 'admin_notices', 'subsactivations_admin_nicess' );
    }
    load_plugin_textdomain( 'subsactivations', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

    // ONLY MOVIE CUSTOM TYPE POSTS
    add_action( 'admin_init' , 'my_column_init' );
    function my_column_init(){
        add_filter('manage_edit-shop_subscription_columns', 'wp_shop_subscription_list_table_columnname');
        add_filter('manage_edit-shop_order_columns', 'wp_shop_orders_list_table_columnname');
        add_action('manage_shop_order_posts_custom_column','wp_wc_order_column_view');
        add_action('manage_shop_subscription_posts_custom_column','wp_wc_subscription_column_view');
    }
    
    // Set custom column in job table
    if (isset($_GET['post_type']) && $_GET['post_type'] == 'shop_subscription') {
        // $this->jobs_list_table_css();
    }
}

register_activation_hook( __FILE__, 'activate_subsactivations_cplgn' );
register_deactivation_hook( __FILE__, 'deactivate_subsactivations_cplgn' );

// Activision function
function activate_subsactivations_cplgn(){
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $subsactivations_v1 = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}subsactivations__v1` (
        `ID` INT NOT NULL AUTO_INCREMENT,
        `user_id` INT NOT NULL,
        `username` VARCHAR(255) NOT NULL,
        `account1` INT NOT NULL,
        `account2` INT NOT NULL,
        PRIMARY KEY (`ID`)) ENGINE = InnoDB";
        dbDelta($subsactivations_v1);
}

// Dectivision function
function deactivate_subsactivations_cplgn(){
    // Nothing For Now
}

// Admin Enqueue Scripts
add_action('admin_enqueue_scripts',function(){
    wp_register_script( SUBSACT_NAME, plugin_dir_url( __FILE__ ).'js/subsactivations-admin.js', array(), 
    microtime(), true );
    wp_enqueue_script(SUBSACT_NAME);
    wp_localize_script( SUBSACT_NAME, 'admin_ajax_action', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' )
    ) );
});

// WP Enqueue Scripts
add_action('wp_enqueue_scripts',function(){
    wp_register_style( SUBSACT_NAME, plugin_dir_url( __FILE__ ).'css/subsactivations-public.css', array(), microtime(), 'all' );
    wp_enqueue_style(SUBSACT_NAME);

    wp_register_script( SUBSACT_NAME, plugin_dir_url( __FILE__ ).'js/subsactivations-public.js', array(), 
    microtime(), true );
    wp_enqueue_script(SUBSACT_NAME);
    wp_localize_script( SUBSACT_NAME, 'subsactivations_actions', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'nonces' )
    ) );
});

// Register Menu
add_action('admin_menu', function(){
    add_menu_page( 'Activations', 'Activations', 'manage_options', 'activations', 'subsactivations_menupage_display', 'dashicons-admin-network', 45 );

    // For url
    add_settings_section( 'subsactivations_addon_section', '', '', 'subsactivations_addon_page' );
    // For colors
    add_settings_section( 'activations_colors_section', '', '', 'activations_colors' );

    // Settings
    add_settings_field( 'subsactivations_url', 'Purchase Url', 'subsactivations_url_func', 'subsactivations_addon_page', 'subsactivations_addon_section');
    register_setting( 'subsactivations_addon_section', 'subsactivations_url');

    // Activate button
    add_settings_field( 'subsactivations_section_activate_btn', 'Activate Button text', 'subsactivations_section_activate_btn_func', 'subsactivations_addon_page', 'subsactivations_addon_section');
    register_setting( 'subsactivations_addon_section', 'subsactivations_section_activate_btn');

    // Hide Activation from order
    add_settings_field( 'subsactivations_hide_order', 'Show Activation from order', 'subsactivations_hide_order_func', 'subsactivations_addon_page', 'subsactivations_addon_section');
    register_setting( 'subsactivations_addon_section', 'subsactivations_hide_order');
    // Hide Activation from subscription
    add_settings_field( 'subsactivations_hide_subscription', 'Show Activation from subscription', 'subsactivations_hide_subscription_func', 'subsactivations_addon_page', 'subsactivations_addon_section');
    register_setting( 'subsactivations_addon_section', 'subsactivations_hide_subscription');
    // Hide Item from subscription
    add_settings_field( 'subsactivations_hide_item', 'Show Item from subscription', 'subsactivations_hide_item_func', 'subsactivations_addon_page', 'subsactivations_addon_section');
    register_setting( 'subsactivations_addon_section', 'subsactivations_hide_item');

    /**
     * COLORS
     */
    //Activate/Dwonload Button
    add_settings_field( 'subsactivations_activate_button', 'Activate', 'activations_activate_button_func', 'activations_colors', 'activations_colors_section');
    register_setting( 'activations_colors_section', 'subsactivations_activate_button');
    // Purchase Button
    add_settings_field( 'subsactivations_purchase_button', 'Purchase Button', 'activations_purchase_button_func', 'activations_colors', 'activations_colors_section');
    register_setting( 'activations_colors_section', 'subsactivations_purchase_button');
    // Section header color
    add_settings_field( 'subsactivations_header_color', 'Heading color', 'subsactivations_header_color_func', 'activations_colors', 'activations_colors_section');
    register_setting( 'activations_colors_section', 'subsactivations_header_color');
    // Section texts color
    add_settings_field( 'subsactivations_txt_color', 'STexts color', 'subsactivations_txt_color_func', 'activations_colors', 'activations_colors_section');
    register_setting( 'activations_colors_section', 'subsactivations_txt_color');
    // Notification color
    add_settings_field( 'subsactivations_notification_color', 'Notification color', 'subsactivations_notification_color_button_func', 'activations_colors', 'activations_colors_section');
    register_setting( 'activations_colors_section', 'subsactivations_notification_color');
    // version color
    add_settings_field( 'subsactivations_version', 'Version', 'subsactivations_version_func', 'activations_colors', 'activations_colors_section');
    register_setting( 'activations_colors_section', 'subsactivations_version');
    // latestversion color
    add_settings_field( 'subsactivations_latestversion', 'Latest Version', 'subsactivations_latestversion_func', 'activations_colors', 'activations_colors_section');
    register_setting( 'activations_colors_section', 'subsactivations_latestversion');
});

/**
 * SETTINGS
 */
// For url input
function subsactivations_url_func(){
    echo '<input type="url" name="subsactivations_url" id="subsactivations_url" value="'.(get_option( 'subsactivations_url', '' ) ? get_option( 'subsactivations_url', '' ):'').'" placeholder="Url">';
}

//subsactivations_section_title
function subsactivations_section_activate_btn_func(){
    echo '<input type="text" name="subsactivations_section_activate_btn" value="'.(get_option( 'subsactivations_section_activate_btn', '' ) ? get_option( 'subsactivations_section_activate_btn', '' ):'').'" placeholder="Activate">';
}

//subsactivations_hide_order
function subsactivations_hide_order_func(){
    echo '<input class="checked" type="checkbox" '.get_option('subsactivations_hide_order','').' name="subsactivations_hide_order" value="'.get_option('subsactivations_hide_order','unchecked').'">';
}
//subsactivations_hide_subscription
function subsactivations_hide_subscription_func(){
    echo '<input class="checked" type="checkbox" '.get_option('subsactivations_hide_subscription','').' name="subsactivations_hide_subscription" value="'.get_option('subsactivations_hide_subscription','unchecked').'">';
}
//subsactivations_hide_item
function subsactivations_hide_item_func(){
    echo '<input class="checked" type="checkbox" '.get_option('subsactivations_hide_item','').' name="subsactivations_hide_item" value="'.get_option('subsactivations_hide_item','unchecked').'">';
}

/**
 * COLORS
 */

// activate/Dwonload Button colors
function activations_activate_button_func(){
    echo '<input type="color" name="subsactivations_activate_button" id="subsactivations_activate_button" value="'.(get_option( 'subsactivations_activate_button', '' ) ? get_option( 'subsactivations_activate_button', '' ):'#3580de').'">';
}
//purchase_button colors
function activations_purchase_button_func(){
    echo '<input type="color" name="subsactivations_purchase_button" id="subsactivations_purchase_button" value="'.(get_option( 'subsactivations_purchase_button', '' ) ? get_option( 'subsactivations_purchase_button', '' ):'#820182').'">';
}

//txt_color_button
function subsactivations_header_color_func(){
    echo '<input type="color" name="subsactivations_header_color" id="subsactivations_header_color" value="'.(get_option( 'subsactivations_header_color', '' ) ? get_option( 'subsactivations_header_color', '' ):'#3a3a3a').'">';
}
//txt_color_button
function subsactivations_txt_color_func(){
    echo '<input type="color" name="subsactivations_txt_color" id="subsactivations_txt_color" value="'.(get_option( 'subsactivations_txt_color', '' ) ? get_option( 'subsactivations_txt_color', '' ):'#3a3a3a').'">';
}
//notification_color
function subsactivations_notification_color_button_func(){
    echo '<input type="color" name="subsactivations_notification_color" id="subsactivations_notification_color" value="'.(get_option( 'subsactivations_notification_color', '' ) ? get_option( 'subsactivations_notification_color', '' ):'#fbad5d').'">';
}
//subsactivations_version
function subsactivations_version_func(){
    echo '<input step="0.01" type="number" name="subsactivations_version" id="subsactivations_version" value="'.(get_option( 'subsactivations_version', '' ) ? get_option( 'subsactivations_version', '' ):'').'" placeholder="'.(get_option( 'subsactivations_version', '' ) ? get_option( 'subsactivations_version', '' ):'1').'">';
}
//subsactivations_latestversion
function subsactivations_latestversion_func(){
    echo '<input step="0.01" type="number" name="subsactivations_latestversion" id="subsactivations_latestversion" value="'.(get_option( 'subsactivations_latestversion', '' ) ? get_option( 'subsactivations_latestversion', '' ):'').'" placeholder="'.(get_option( 'subsactivations_latestversion', '' ) ? get_option( 'subsactivations_latestversion', '' ):'1').'">';
}

// subsactivations_reset_colors
add_action("wp_ajax_subsactivations_reset_colors", "subsactivations_reset_colors");
add_action("wp_ajax_nopriv_subsactivations_reset_colors", "subsactivations_reset_colors");
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
        wp_enqueue_script(SUBSACT_NAME);
        ?>
        <style>
            p.submit { display: inline-block; }
            button#rest_color { padding: 7px 10px; background: red; border: none; outline: none; border-radius: 3px; margin-left: 10px; color: #fff; cursor: pointer; opacity: .7; } button#rest_color:hover{ opacity: 1;}
        </style>
        <?php

        echo '<form action="options.php" method="post" id="subsactivations_url">';
        echo '<h1>Settings</h1>';
        echo '<table class="form-table">';

        settings_fields( 'subsactivations_addon_section' );
        do_settings_fields( 'subsactivations_addon_page', 'subsactivations_addon_section' );

        echo '</table>';
        submit_button('Save');
        echo '</form>';


        echo '<form action="options.php" method="post" id="activations_colors">';
        echo '<h1>Activation Colors</h1><hr>';
        echo '<table class="form-table">';

        settings_fields( 'activations_colors_section' );
        do_settings_fields( 'activations_colors', 'activations_colors_section' );
        
        echo '</table>';
        submit_button();
        echo '<button id="rest_color">Reset</button>';
        echo '</form>';
        ?>
        <?php
    }
}

// Output with Shortcode
add_shortcode('activations_v1', 'subsactivations_output');
require_once 'inc/subsactivations-output.php';

function send_post_request_to_json($namespace, $data = array()){
    if(!empty($data)){
        $data = json_encode($data);
        $url = ACTIVATION_REST_URL.'/'.$namespace;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);
        $obj = json_decode($result);

        return $obj;
    }
}

/**
 * { AJAX CALLING FOR INSERTING AND UPDATING }
 */
add_action("wp_ajax_subsactivations_data_check", "subsactivations_data_check");
add_action("wp_ajax_nopriv_subsactivations_data_check", "subsactivations_data_check");
function subsactivations_data_check(){
    if(wp_verify_nonce( $_POST['nonce'], 'nonces' )){
        global $wpdb,$current_user;
        $number_1 = intval($_POST['number_1']);
        $number_2 = intval($_POST['number_2']);


        // Insert data if data not exist into external server
        if(!$_SESSION['account1'] || !$_SESSION['account2']){
            $_SESSION['account1'] = $number_1;
            $_SESSION['account2'] = $number_2;

            if(!empty($number_1)){
                $datarest = array(
                    "key" =>  REST_API_KEY,
                    "account_no" =>  $number_1,
                    "user_name"  => $current_user->user_login,
                    "version"  => (get_option('subsactivations_version')? get_option('subsactivations_version'):'1'),
                    "latest_version"  => (get_option('subsactivations_latestversion')? get_option('subsactivations_latestversion'):'1'),
                );
                send_post_request_to_json(ACTIVATION_REST_CREATE, $datarest);
            }

            if(!empty($number_2)){
                $datarest = array(
                    "key" =>  REST_API_KEY,
                    "account_no" =>  $number_2,
                    "user_name"  => $current_user->user_login,
                    "version"  => (get_option('subsactivations_version')? get_option('subsactivations_version'):'1'),
                    "latest_version"  => (get_option('subsactivations_latestversion')? get_option('subsactivations_latestversion'):'1'),
                );
                send_post_request_to_json(ACTIVATION_REST_CREATE, $datarest);
            }
        }
        
        $table = $wpdb->prefix.'subsactivations__v1';
        $data = $wpdb->get_row("SELECT * FROM $table WHERE user_id = $current_user->ID");

        //For email
        $admin_email = get_option( 'admin_email' );
        $user_email = $current_user->user_email;
        $subject = 'FP-('.$current_user->ID.')-('.$current_user->display_name.')';

        if($data){
            $wpdb->update($table, array(
                'account1' => $number_1,
                'account2' => $number_2,
                'username' => $current_user->display_name 
            ),array(
                "user_id" => $current_user->ID
            ),array('%d','%d','%s'),array('%d'));

            if(!empty($number_1)){
                // UPDATE $number_1 DATA TO EXTERNAL DB (REST URL)
                $datarest = array(
                    "key" =>  REST_API_KEY,
                    "old_account" =>  intval($_SESSION['account1']),
                    "account_no" =>  $number_1,
                    "user_name"  => $current_user->user_login,
                    "version"  => (get_option('subsactivations_version')? get_option('subsactivations_version'):'1'),
                    "latest_version"  => (get_option('subsactivations_latestversion')? get_option('subsactivations_latestversion'):'1'),
                );
                send_post_request_to_json(ACTIVATION_REST_UPDATE, $datarest);
                // Set number for identifying
                $_SESSION['account1'] = $number_1;
            }
            if(!empty($number_2)){
                // UPDATE $number_1 DATA TO EXTERNAL DB (REST URL)
                $datarest = array(
                    "key" =>  REST_API_KEY,
                    "old_account" =>  intval($_SESSION['account2']),
                    "account_no" =>  $number_2,
                    "user_name"  => $current_user->user_login,
                    "version"  => (get_option('subsactivations_version')? get_option('subsactivations_version'):'1'),
                    "latest_version"  => (get_option('subsactivations_latestversion')? get_option('subsactivations_latestversion'):'1'),
                );
                send_post_request_to_json(ACTIVATION_REST_UPDATE, $datarest);
                 // Set number for identifying
                $_SESSION['account2'] = $number_2;
            }

            if ( !is_wp_error( $wpdb ) ) {

                if(!empty($number_1) && intval($data->account1) !== $number_1 && !empty($number_2) && intval($data->account2) == $number_2){
                    echo wp_json_encode(array('changed' => 'Account license changed from <span class="number">#'. intval($data->account1) .'</span> to  <span class="number">#'. $number_1.'</span>'));
                    
                    // Send to admin
                    if(!current_user_can( 'administrator' )){
                        $message = $user_email. ' Changed license from '.intval($data->account1).' to '.$number_1;
                        wp_mail($admin_email, $subject, $message);
                    }

                    wp_die();
                }

                if(!empty($number_1) && intval($data->account1) !== $number_1 && !empty($number_2) && intval($data->account2) !== $number_2){
                    echo wp_json_encode(array('changedboth' => 'Account license changed from <span class="number">#'. intval($data->account1) .'</span> to <span class="number">#'. $number_1 .'</span>', 'successboth' => 'Account <span class="number"> #'.$number_2.' </span> is activated.'));

                    // Send to admin
                    if(!current_user_can( 'administrator' )){
                        $message = $user_email. " Changed license from #".intval($data->account1)." to #".$number_1.".\n#".$number_2." accounts activated.";
                        wp_mail($admin_email, $subject, $message);
                    }
                    
                    wp_die();
                }

                if(!empty($number_2) && intval($data->account2) !== $number_2 && !empty($number_1) && intval($data->account1) == $number_1){
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

                    wp_die();
                }
                
                if(!empty($number_2) && intval($data->account2) == $number_2){
                    echo wp_json_encode(array('error' => 'Already Exist!'));
                    wp_die();
                }
                
            }else{
                echo wp_json_encode(array('error' => '🙄Error. Try again!'));
                wp_die();
            }
        }else{
            $wpdb->insert($table, array(
                'user_id' => $current_user->ID,
                'account1' => $number_1,
                'account2' => $number_2,
                'username' => $current_user->display_name
            ),array('%d','%d','%d','%s'));

            $_SESSION['account1'] = $number_1;
            $_SESSION['account2'] = $number_2;

            if(!empty($number_1)){
                $datarest = array(
                    "key" =>  REST_API_KEY,
                    "account_no" =>  $number_1,
                    "user_name"  => $current_user->user_login,
                    "version"  => (get_option('subsactivations_version')? get_option('subsactivations_version'):'1'),
                    "latest_version"  => (get_option('subsactivations_latestversion')? get_option('subsactivations_latestversion'):'1'),
                );
                send_post_request_to_json(ACTIVATION_REST_CREATE, $datarest);
            }

            if(!empty($number_2)){
                $datarest = array(
                    "key" =>  REST_API_KEY,
                    "account_no" =>  $number_2,
                    "user_name"  => $current_user->user_login,
                    "version"  => (get_option('subsactivations_version')? get_option('subsactivations_version'):'1'),
                    "latest_version"  => (get_option('subsactivations_latestversion')? get_option('subsactivations_latestversion'):'1'),
                );
                send_post_request_to_json(ACTIVATION_REST_CREATE, $datarest);
            }

            if ( !is_wp_error( $wpdb ) ) {
                if(empty($number_2)){
                    echo wp_json_encode(array('success' => 'Account <span class="number">'.$number_1.'</span> is activated.' ));

                    // Send to admin
                    if(!current_user_can( 'administrator' )){
                        $message = $user_email.' activated '. $number_1.' account.';
                        wp_mail($admin_email, $subject ,$message);
                    }
                }else{
                    echo wp_json_encode(array('success' => 'Account <span class="number"> '.$number_2.' </span>is activated.'));
                    
                    // Send to admin
                    if(!current_user_can( 'administrator' )){
                        $message = $user_email.' activated #'. $number_2.' account.';
                        wp_mail($admin_email, $subject, $message);
                    }
                }
                wp_die();
            }else{
                echo wp_json_encode(array('error' => 'Error.'));
                wp_die();
            }
        }
        wp_die();
    }
    wp_die();
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
    $userdata = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}subsactivations__v1 WHERE user_id = $user_id");
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