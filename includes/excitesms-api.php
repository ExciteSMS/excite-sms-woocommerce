<?php
function send_sms_using_excitesms($recipient, $message) {
    $api_key = get_option('excitesms_api_key');
    $sender_id = get_option('excitesms_sender_id');

    if (empty($api_key) || empty($sender_id)) {
        // Handle missing API key or sender ID
        return new WP_Error('excitesms_missing_settings', 'API Key or Sender ID is missing.');
    }

    $url = 'https://portal.excitesms.tech/api/v3/sms/send';

    $data = array(
        'recipient' => $recipient,
        'sender_id' => $sender_id,
        'type' => 'plain',
        'message' => $message
    );

    $headers = array(
        'Authorization: Bearer ' . $api_key,
        'Content-Type: application/json',
        'Accept: application/json'
    );

    $response = wp_safe_remote_post($url, array(
        'headers' => $headers,
        'body' => json_encode($data)
    ));

    if (is_wp_error($response)) {
        // Handle API request error
        return $response;
    } else {
        // Handle success
        return wp_remote_retrieve_body($response);
    }
}
