<?php
register_activation_hook( __FILE__, 'activate_subsactivations_cplgn' );
register_deactivation_hook( __FILE__, 'deactivate_subsactivations_cplgn' );
add_action( 'plugins_loaded', 'subsactivations_dependency' );
add_action( 'admin_init' , 'my_column_init' );
// subsactivations_reset_colors
add_action("wp_ajax_subsactivations_reset_colors", "subsactivations_reset_colors");
add_action("wp_ajax_nopriv_subsactivations_reset_colors", "subsactivations_reset_colors");
// Output with Shortcode
add_shortcode('activations_v1', 'subsactivations_output');
// Output single_post with Shortcode
add_shortcode('single_post_v1', 'activations_post_show');
add_action("wp_ajax_subsactivations_data_check", "subsactivations_data_check");
add_action("wp_ajax_nopriv_subsactivations_data_check", "subsactivations_data_check");
add_action("wp_ajax_subsactivations_mtids_store", "subsactivations_mtids_store");
add_action("wp_ajax_nopriv_subsactivations_mtids_store", "subsactivations_mtids_store");
add_filter ( 'woocommerce_account_menu_items', 'junu_single_post_show', 40 );
add_action( 'init', 'junu_endpoints' );
add_action( 'woocommerce_account_single_post_endpoint', 'junu_my_account_endpoint_single_post' );
