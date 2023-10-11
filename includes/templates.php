<?php
// Function to save custom SMS templates
function save_sms_template($template_name, $template_content) {
    $templates = get_option('excitesms_templates', array());
    $templates[$template_name] = $template_content;
    update_option('excitesms_templates', $templates);
}

// Function to retrieve SMS templates
function get_sms_templates() {
    return get_option('excitesms_templates', array());
}

// Function to delete an SMS template
function delete_sms_template($template_name) {
    $templates = get_option('excitesms_templates', array());
    if (isset($templates[$template_name])) {
        unset($templates[$template_name]);
        update_option('excitesms_templates', $templates);
    }
}
