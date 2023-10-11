<?php
function send_sms_using_excitesms_with_template($recipient, $template_name, $template_variables) {
    $api_key = get_option('excitesms_api_key');
    $sender_id = get_option('excitesms_sender_id');

    if (empty($api_key) || empty($sender_id)) {
        return new WP_Error('excitesms_missing_settings', 'API Key or Sender ID is missing.');
    }

    $templates = get_sms_templates();

    if (isset($templates[$template_name])) {
        $template_content = $templates[$template_name];

        // Replace template variables with actual data
        foreach ($template_variables as $variable => $value) {
            $template_content = str_replace("{{$variable}}", $value, $template_content);
        }

        $url = 'https://portal.excitesms.tech/api/v3/sms/send';

        $data = array(
            'recipient' => $recipient,
            'sender_id' => $sender_id,
            'type' => 'plain',
            'message' => $template_content
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
            return $response;
        } else {
            return wp_remote_retrieve_body($response);
        }
    } else {
        return new WP_Error('excitesms_template_not_found', 'Template not found.');
    }
}
