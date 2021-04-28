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
    wp_localize_script( SUBSACT_NAME, 'subsactivations_actions', array(
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
});

// Menu callback funnction
function subsactivations_menupage_display(){
    wp_enqueue_script(SUBSACT_NAME);
    ?>
    <table class="form-table">
        <tbody>
            <tr>
                <th scope="row">Empty Table</th>
                <td>Will Using later</td>
            </tr>
        </tbody>
    </table>
    <?php
}

// Output with Shortcode
add_shortcode('activations_v1', 'subsactivations_output');
require_once 'inc/subsactivations-output.php';

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

        if($data){
            $wpdb->update($table, array(
                'account2' => $number_2,
                'username' => $current_user->display_name 
            ),array(
                "user_id" => $current_user->ID
            ),array('%d','%d','%s'),array('%d'));

        }else{
            $wpdb->insert($table, array(
                'user_id' => $current_user->ID,
                'account1' => $number_1,
                'account2' => $number_2,
                'username' => $current_user->display_name
            ),array('%d','%d','%d','%s')); 
        }

        if ( !is_wp_error( $wpdb ) ) {
            echo wp_json_encode(array('success' => 'Success'));
            wp_die();
        }else{
            echo wp_json_encode(array('error' => 'Error.'));
            wp_die();
        }
        wp_die();
    }
    wp_die();
}