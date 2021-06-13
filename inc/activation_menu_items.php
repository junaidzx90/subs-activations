<?php

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
//subsactivations_post_id_func
function subsactivations_post_id_func(){
    echo '<input type="text" name="subsactivations_post_id" value="'.(get_option( 'subsactivations_post_id', '' ) ? get_option( 'subsactivations_post_id', '' ):'').'" placeholder="Post ID">';
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