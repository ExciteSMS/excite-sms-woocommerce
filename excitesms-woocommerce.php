<?php
/*
Plugin Name: ExciteSMS for WooCommerce
Description: Send SMS notifications using ExciteSMS.
Version: 1.0
Author: Kazashim Kuzasuwat
*/

// Include admin settings
include('admin/admin.php');

// Include API functions
include('includes/excitesms-api.php');

// Send SMS when a new order is placed
function excitesms_send_order_sms($order_id) {
    $order = wc_get_order($order_id);
    
    $recipient = $order->get_billing_phone(); // 
    $message = 'Thank you for your order with us. Your order ID is ' . $order_id;

    $response = send_sms_using_excitesms($recipient, $message);

    if (is_wp_error($response)) {
        // Handle error
    } else {
        // Handle success
    }
}
add_action('woocommerce_new_order', 'excitesms_send_order_sms');
