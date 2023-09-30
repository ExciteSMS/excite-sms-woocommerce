# ExciteSMS for WooCommerce Plugin Documentation

## Table of Contents

- [ExciteSMS for WooCommerce Plugin Documentation](#excitesms-for-woocommerce-plugin-documentation)
  - [Table of Contents](#table-of-contents)
  - [1. Introduction](#1-introduction)
  - [2. Installation](#2-installation)
  - [3. Configuration](#3-configuration)
  - [4. Usage](#4-usage)
  - [5. Troubleshooting](#5-troubleshooting)
  - [6. Frequently Asked Questions (FAQ)](#6-frequently-asked-questions-faq)
  - [7. Support](#7-support)
  - [8. Contributing](#8-contributing)
  - [9. License](#9-license)

---

## 1. Introduction<a name="introduction"></a>

The ExciteSMS for WooCommerce plugin allows you to send SMS notifications using the ExciteSMS API for WooCommerce store events. This documentation provides step-by-step instructions on how to install, configure, and use the plugin effectively.

---

## 2. Installation<a name="installation"></a>

Follow these steps to install the ExciteSMS for WooCommerce plugin:

1. **Download the Plugin:**
   - You can download the plugin from [GitHub](https://github.com/ExciteSMS/excite-sms-woocommerce) or the WordPress plugin repository.
   - Alternatively, you can search for "ExciteSMS for WooCommerce" in the WordPress Admin Dashboard under "Plugins" -> "Add New" and click "Install Now."

2. **Activate the Plugin:**
   - Once installed, click "Activate" to enable the plugin.

---

## 3. Configuration<a name="configuration"></a>

To configure the plugin settings, follow these steps:

1. **Navigate to WooCommerce Settings:**
   - In your WordPress Admin Dashboard, go to "WooCommerce" -> "Settings."

2. **Configure ExciteSMS Settings:**
   - Click on the "ExciteSMS" tab.
   - Enter your ExciteSMS API Key and Sender ID.
   - Click "Save changes."

---

## 4. Usage<a name="usage"></a>

The ExciteSMS for WooCommerce plugin sends SMS notifications when an order is marked as completed. There's no additional action required to trigger the SMS notification; it will be sent automatically when an order reaches the "completed" status.

---

## 5. Troubleshooting<a name="troubleshooting"></a>

If you encounter any issues or errors while using the plugin, consider the following troubleshooting steps:

1. **Check Configuration:**
   - Ensure that you have entered the correct ExciteSMS API Key and Sender ID in the plugin settings.

2. **Error Logs:**
   - Check your website's error logs for any relevant error messages.

3. **Plugin Conflicts:**
   - Disable other plugins one by one to check if there are any conflicts.

4. **Support:**
   - If the issue persists, refer to the [Support](#support) section for assistance.

---

## 6. Frequently Asked Questions (FAQ)<a name="faq"></a>

**Q1: Can I customize the SMS message that is sent to customers?**

Yes, you can customize the SMS message by modifying the `excite_sms_send_sms_on_order_completed` function in the plugin code. Look for the `$message` variable in the function and adjust it as needed.

---

## 7. Support<a name="support"></a>

If you encounter any issues, have questions, or need assistance, please contact our support team at [sms@excitesms.tec](mailto:sms@excitesms.tech) or visit our [support page](https://excitesms.tech).

---

## 8. Contributing<a name="contributing"></a>

We welcome contributions to improve this plugin. If you'd like to contribute, please submit a pull request on [GitHub](#plugin-github-link).

---

## 9. License<a name="license"></a>

The ExciteSMS for WooCommerce plugin is licensed under the [GNU General Public License (GPL) version 2](https://www.gnu.org/licenses/gpl-2.0.html).
