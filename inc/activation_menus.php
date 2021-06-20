<?php
// Register Menu
add_action('admin_menu', function(){
    add_menu_page( 'Activations', 'Activations', 'manage_options', 'activations', 'subsactivations_menupage_display', 'dashicons-admin-network', 45 );

    // For url
    add_settings_section( 'subsactivations_settings_section', '', '', 'subsactivations_settings_page' );
    // For colors
    add_settings_section( 'activations_colors_section', '', '', 'activations_colors' );

    // Settings
    add_settings_field( 'subsactivations_url', 'Purchase Url', 'subsactivations_url_func', 'subsactivations_settings_page', 'subsactivations_settings_section');
    register_setting( 'subsactivations_settings_section', 'subsactivations_url');
    // Purchase btn texts
    add_settings_field( 'subsactivations_purchase_txt', 'Purchase button text', 'subsactivations_purchase_txt_func', 'subsactivations_settings_page', 'subsactivations_settings_section');
    register_setting( 'subsactivations_settings_section', 'subsactivations_purchase_txt');

    // Activate button
    add_settings_field( 'subsactivations_section_activate_btn', 'Activate Button text', 'subsactivations_section_activate_btn_func', 'subsactivations_settings_page', 'subsactivations_settings_section');
    register_setting( 'subsactivations_settings_section', 'subsactivations_section_activate_btn');

    // Post Id
    add_settings_field( 'subsactivations_post_id', 'Post Id', 'subsactivations_post_id_func', 'subsactivations_settings_page', 'subsactivations_settings_section');
    register_setting( 'subsactivations_settings_section', 'subsactivations_post_id');

    // Hide Activation from order
    add_settings_field( 'subsactivations_hide_order', 'Show Activation from order', 'subsactivations_hide_order_func', 'subsactivations_settings_page', 'subsactivations_settings_section');
    register_setting( 'subsactivations_settings_section', 'subsactivations_hide_order');
    // Hide Activation from subscription
    add_settings_field( 'subsactivations_hide_subscription', 'Show Activation from subscription', 'subsactivations_hide_subscription_func', 'subsactivations_settings_page', 'subsactivations_settings_section');
    register_setting( 'subsactivations_settings_section', 'subsactivations_hide_subscription');
    // Hide Item from subscription
    add_settings_field( 'subsactivations_hide_item', 'Show Item from subscription', 'subsactivations_hide_item_func', 'subsactivations_settings_page', 'subsactivations_settings_section');
    register_setting( 'subsactivations_settings_section', 'subsactivations_hide_item');

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