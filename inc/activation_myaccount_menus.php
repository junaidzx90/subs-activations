<?php

/*
* Step 1. Add Link (Tab) to My Account menu
*/

function junu_single_post_show( $menu_links ){

    $menu_links = array_slice( $menu_links, 0, 5, true ) 
    + array( 'single_post' => 'Single post' )
    + array_slice( $menu_links, 5, NULL, true );

    return $menu_links;
}

/*
* Step 2. Register Permalink Endpoint
*/

function junu_endpoints() {
    add_rewrite_endpoint( 'single_post', EP_PAGES );
}

/*
* Step 3. Content for the new page in My Account, woocommerce_account_{ENDPOINT NAME}_endpoint
*/

function junu_my_account_endpoint_single_post() {
    echo do_shortcode( '[single_post_v1]' );
}


// removeable
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
add_action( 'init', 'junu_extra_endpoints' );
function junu_extra_endpoints() {
    add_rewrite_endpoint( 'activations', EP_PAGES );
}

/*
* Step 3. Content for the new page in My Account, woocommerce_account_{ENDPOINT NAME}_endpoint
*/
add_action( 'woocommerce_account_activations_endpoint', 'junu_my_account_endpoint_content' );
function junu_my_account_endpoint_content() {
    echo do_shortcode( '[activations_v1]' );
}