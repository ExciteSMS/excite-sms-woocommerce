<?php
// Add a menu item to the WooCommerce settings menu
// Include the templates file
include('includes/templates.php');

// Add a custom tab for SMS templates
function excitesms_add_templates_menu() {
    add_submenu_page(
        'woocommerce',
        'SMS Templates',
        'SMS Templates',
        'manage_options',
        'sms-templates',
        'sms_templates_page'
    );
}
add_action('admin_menu', 'excitesms_add_templates_menu');

// Create the SMS templates page
function sms_templates_page() {
    if (isset($_POST['submit_template'])) {
        $template_name = sanitize_text_field($_POST['template_name']);
        $template_content = sanitize_text_field($_POST['template_content']);
        save_sms_template($template_name, $template_content);
    }

    if (isset($_GET['delete_template'])) {
        $template_name = sanitize_text_field($_GET['delete_template']);
        delete_sms_template($template_name);
    }

    $templates = get_sms_templates();

    ?>
    <div class="wrap">
        <h2>SMS Templates</h2>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th>Template Name</th>
                    <th>Template Content</th>
                    <th>Actions</th>
                </tr>
                <?php
                foreach ($templates as $template_name => $template_content) {
                    echo '<tr>';
                    echo '<td>' . esc_html($template_name) . '</td>';
                    echo '<td>' . esc_html($template_content) . '</td>';
                    echo '<td><a href="?page=sms-templates&delete_template=' . esc_attr($template_name) . '">Delete</a></td>';
                    echo '</tr>';
                }
                ?>
            </table>
            <h3>Add New Template</h3>
            <table class="form-table">
                <tr>
                    <th>Template Name</th>
                    <td><input type="text" name="template_name" required></td>
                </tr>
                <tr>
                    <th>Template Content</th>
                    <td><textarea name="template_content" required></textarea></td>
                </tr>
            </table>
            <p>
                <input type="submit" name="submit_template" class="button-primary" value="Save Template">
            </p>
        </form>
    </div>
    <?php
}
