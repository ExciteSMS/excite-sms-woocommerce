jQuery(document).ready(function($) {
    $('#excite-sms-send-test').click(function() {
        $.ajax({
            url: excite_sms_ajax.ajaxurl,
            type: 'POST',
            data: {
                action: 'excite_sms_send_test_message',
            },
            success: function(response) {
                alert(response);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
});
