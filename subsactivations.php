<?php
 /**
 *
 * @link              https://github.com/
 * @since             1.0.0
 * @package           subsactivations
 *
 * @wordpress-plugin
 * Plugin Name:       Activations
 * Plugin URI:        https://github.com/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Mql4Expert.com
 * Author URI:        Mql4Expert.com
 * Text Domain:       subsactivations
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
function subsactivations_init() {
    if(!class_exists('WC_Subscriptions')){
        add_action( 'admin_notices', 'subsactivations_admin_nicess' );
    }
}
add_action( 'plugins_loaded', 'subsactivations_init' );

function subsactivations_admin_nicess(){
    $message = sprintf(
		/* translators: 1: Plugin Name 2: Elementor */
		esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'subsactivations' ),
		'<strong>' . esc_html__( 'Activations', 'subsactivations' ) . '</strong>',
		'<strong>' . esc_html__( 'Woocommerce Subscriptions', 'subsactivations' ) . '</strong>'
	);

	printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
}
define( 'SUBSACT_NAME', 'subsactivations' );
define( 'SUBSACT_PATH', plugin_dir_path( __FILE__ ) );

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
    // For colors
    add_settings_section( 'activations_colors_section', 'Activation Colors', '', 'activations_colors' );
    // For url
    add_settings_section( 'subsactivations_url_section', 'Purchase Url', '', 'subsactivations_url_page' );

    //Activate Button
    add_settings_field( 'subsactivations_activate_button', 'Activate Button', 'activations_activate_button_func', 'activations_colors', 'activations_colors_section');
    register_setting( 'activations_colors_section', 'subsactivations_activate_button');
    // Purchase Button
    add_settings_field( 'subsactivations_purchase_button', 'Purchase Button', 'activations_purchase_button_func', 'activations_colors', 'activations_colors_section');
    register_setting( 'activations_colors_section', 'subsactivations_purchase_button');
    // Form text colors
    add_settings_field( 'subsactivations_txt_color', 'Form text colors', 'subsactivations_txt_color_button_func', 'activations_colors', 'activations_colors_section');
    register_setting( 'activations_colors_section', 'subsactivations_txt_color');
    // Notification color
    add_settings_field( 'subsactivations_notification_color', 'Notification color', 'subsactivations_notification_color_button_func', 'activations_colors', 'activations_colors_section');
    register_setting( 'activations_colors_section', 'subsactivations_notification_color');

    // Url
    add_settings_field( 'subsactivations_url', 'Purchase Url', 'subsactivations_url_func', 'subsactivations_url_page', 'subsactivations_url_section');
    register_setting( 'subsactivations_url_section', 'subsactivations_url');
});

// activate Button colors
function activations_activate_button_func(){
    echo '<input type="color" name="subsactivations_activate_button" id="subsactivations_activate_button" value="'.(get_option( 'subsactivations_activate_button', '' ) ? get_option( 'subsactivations_activate_button', '' ):'#3580de').'">';
}
//purchase_button colors
function activations_purchase_button_func(){
    echo '<input type="color" name="subsactivations_purchase_button" id="subsactivations_purchase_button" value="'.(get_option( 'subsactivations_purchase_button', '' ) ? get_option( 'subsactivations_purchase_button', '' ):'#820182').'">';
}
//txt_color_button
function subsactivations_txt_color_button_func(){
    echo '<input type="color" name="subsactivations_txt_color" id="subsactivations_txt_color" value="'.(get_option( 'subsactivations_txt_color', '' ) ? get_option( 'subsactivations_txt_color', '' ):'#3a3a3a').'">';
}
//notification_color
function subsactivations_notification_color_button_func(){
    echo '<input type="color" name="subsactivations_notification_color" id="subsactivations_notification_color" value="'.(get_option( 'subsactivations_notification_color', '' ) ? get_option( 'subsactivations_notification_color', '' ):'#fbad5d').'">';
}


// For url input
function subsactivations_url_func(){
    echo '<input type="url" name="subsactivations_url" id="subsactivations_url" value="'.(get_option( 'subsactivations_url', '' ) ? get_option( 'subsactivations_url', '' ):'').'" placeholder="Url">';
}

// subsactivations_reset_colors
add_action("wp_ajax_subsactivations_reset_colors", "subsactivations_reset_colors");
add_action("wp_ajax_nopriv_subsactivations_reset_colors", "subsactivations_reset_colors");
function subsactivations_reset_colors(){
    delete_option( 'subsactivations_activate_button' );
    delete_option( 'subsactivations_purchase_button' );
    delete_option( 'subsactivations_txt_color' );
    delete_option( 'subsactivations_notification_color' );
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

        echo '<form action="options.php" method="post" id="subsactivations_url">';
        echo '<h1>Purchase Url</h1>';
        echo '<table class="form-table">';

        settings_fields( 'subsactivations_url_section' );
        do_settings_fields( 'subsactivations_url_page', 'subsactivations_url_section' );

        echo '</table>';
        submit_button('Save');
        echo '</form>';
    }
}

// Output with Shortcode
add_shortcode('activations_v1', 'subsactivations_output');
require_once 'inc/subsactivations-output.php';

/*
 * Step 1. Add Link (Tab) to My Account menu
 */
add_filter ( 'woocommerce_account_menu_items', 'junu_actiovations_link', 40 );
function junu_actiovations_link( $menu_links ){
 
	$menu_links = array_slice( $menu_links, 0, 5, true ) 
	+ array( 'activations' => 'Activations' )
	+ array_slice( $menu_links, 5, NULL, true );
 
	return $menu_links;
}

/*
 * Step 2. Register Permalink Endpoint
 */
add_action( 'init', 'junu_endpoints' );
function junu_endpoints() {
	add_rewrite_endpoint( 'activations', EP_PAGES );
}

/*
 * Step 3. Content for the new page in My Account, woocommerce_account_{ENDPOINT NAME}_endpoint
*/
add_action( 'woocommerce_account_activations_endpoint', 'junu_my_account_endpoint_content' );
function junu_my_account_endpoint_content() {
	echo do_shortcode( '[activations_v1]' );
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

        $table = $wpdb->prefix.'subsactivations__v1';
        $data = $wpdb->get_row("SELECT * FROM $table WHERE user_id = $current_user->ID");

        $admin_email = get_option( 'admin_email' );
        $user_email = $current_user->user_email;

        if($data){
            $wpdb->update($table, array(
                'account1' => $number_1,
                'account2' => $number_2,
                'username' => $current_user->display_name 
            ),array(
                "user_id" => $current_user->ID
            ),array('%d','%d','%s'),array('%d'));

            if ( !is_wp_error( $wpdb ) ) {

                if(!empty($number_1) && intval($data->account1) !== $number_1 && !empty($number_2) && intval($data->account2) == $number_2){
                    echo wp_json_encode(array('changed' => 'Account licence changed from <span class="number">#'. intval($data->account1) .'</span> to  <span class="number">#'. $number_1.'</span>'));
                    
                    // Send to admin
                    if(!current_user_can( 'administrator' )){
                        $message = $user_email. ' Changed Licence from '.intval($data->account1).' to '.$number_1;
                        wp_mail($admin_email,'Licence Changed #'.$number_1, $message);
                    }

                    if(current_user_can( 'subscriber' )){
                        $message = 'Changed Licence from '.intval($data->account1).' to '.$number_1;
                        wp_mail($user_email,'Licence Changed #'.$number_1, $message);
                    }
                    wp_die();
                }

                if(!empty($number_1) && intval($data->account1) !== $number_1 && !empty($number_2) && intval($data->account2) !== $number_2){
                    echo wp_json_encode(array('changedboth' => 'Account licence changed from <span class="number">#'. intval($data->account1) .'</span> to <span class="number">#'. $number_1 .'</span>', 'successboth' => 'Account <span class="number"> #'.$number_2.' </span> is activated.'));

                    // Send to admin
                    if(!current_user_can( 'administrator' )){
                        $message = $user_email. " Changed Licence from #".intval($data->account1)." to #".$number_1.".\n#".$number_2." accounts activated.";
                        wp_mail($admin_email,'Account Activate #'.$number_2, $message);
                    }

                    if(current_user_can( 'subscriber' )){
                        $message = "Changed Licence from #".intval($data->account1)." to #".$number_1.".\n#".$number_2." accounts activated.";
                        wp_mail($user_email,'Your account has been activated!', $message);
                    }
                    
                    wp_die();
                }

                if(!empty($number_2) && intval($data->account2) !== $number_2 && !empty($number_1) && intval($data->account1) == $number_1){
                    echo wp_json_encode(array('success' => 'Account <span class="number"> #'.$number_2.' </span> is activated.' ));

                    // Send to admin
                    if(!current_user_can( 'administrator' )){
                        $message = $user_email.' Activated #'. $number_2.' account.';
                        wp_mail($admin_email,'Account Activate #'.$number_2, $message);
                    }

                    if(current_user_can( 'subscriber' )){
                        $message = 'Activated #'. $number_2.' account.';
                        wp_mail($user_email,'Your account has been activated!', $message);
                    }
                    wp_die();
                }

                if(empty($number_2) && !empty($number_1)){
                    echo wp_json_encode(array('success' => 'Account <span class="number"> #'.$number_1.' </span> is activated.' ));

                    // Send to admin
                    if(!current_user_can( 'administrator' )){
                        $message = $user_email.' activated #'. $number_1.' account.';
                        wp_mail($admin_email,'Account Activate #'.$number_1,$message);
                    }

                    if(current_user_can( 'subscriber' )){
                        $message = 'Activated #'. $number_1.' account.';
                        wp_mail($user_email,'Your account has been activated!', $message);
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

            if ( !is_wp_error( $wpdb ) ) {
                if(empty($number_2)){
                    echo wp_json_encode(array('success' => 'Account <span class="number">'.$number_1.'</span> is activated.' ));

                    // Send to admin
                    if(!current_user_can( 'administrator' )){
                        $message = $user_email.' activated '. $number_1.' account.';
                        wp_mail($admin_email,'New User',$message);
                    }

                    if(current_user_can( 'subscriber' )){
                        $message = 'Activated #'. $number_1.' account.';
                        wp_mail($user_email,'Your account has been activated!',$message);
                    }
                }else{
                    echo wp_json_encode(array('success' => 'Account <span class="number"> '.$number_2.' </span>is activated.'));
                    
                    // Send to admin
                    if(!current_user_can( 'administrator' )){
                        $message = $user_email.' activated #'. $number_2.' account.';
                        wp_mail($admin_email,'New User',$message);
                    }

                    if(current_user_can( 'subscriber' )){
                        $message = 'Activated #'. $number_2.' account.';
                        wp_mail($admin_email,'Your account has been activated!',$message);
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