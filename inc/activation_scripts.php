<?php
// Admin Enqueue Scripts
add_action('admin_enqueue_scripts',function(){
    // admin css
    if (isset($_GET['page']) && $_GET['page'] == 'activations') {
        wp_enqueue_style( 'admin-view', SUBSACT_URL.'css/admin-view.css', array(), microtime(), 'all' );
        wp_enqueue_style( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), microtime(), 'all' );
    }

    wp_register_script( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array(), 
    microtime(), true );
    wp_enqueue_script('select2');
    
    wp_register_script( SUBSACT_NAME, SUBSACT_URL.'js/subsactivations-admin.js', array(), 
    microtime(), true );
    wp_enqueue_script(SUBSACT_NAME);
    wp_localize_script( SUBSACT_NAME, 'admin_ajax_action', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' )
    ) );
});

// WP Enqueue Scripts
add_action('wp_enqueue_scripts',function(){
    wp_register_style( SUBSACT_NAME, SUBSACT_URL.'css/subsactivations-public.css', array(), microtime(), 'all' );
    wp_enqueue_style(SUBSACT_NAME);

    wp_register_script( SUBSACT_NAME, SUBSACT_URL.'js/subsactivations-public.js', array(), 
    microtime(), true );
    wp_enqueue_script(SUBSACT_NAME);
    wp_localize_script( SUBSACT_NAME, 'subsactivations_actions', array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'nonces' )
    ) );
});