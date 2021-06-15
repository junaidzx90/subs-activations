jQuery(function ($) {

    function junu_alert_info(txt) {
        $('.alert_info').remove();
        $('.ufields').append('<span class="alert_info">'+txt+'</span>');
        $('.alert_info').animate({'right': '15px'});
        setTimeout(() => {
            $('.alert_info').remove();
        }, 6000);
    }

    // Subscription account number
    $('#subsactivations-subtn').on("click", function (e) {
        e.preventDefault();

        let number_1 = $('#ac_number_1').val();
        let number_2 = $('#ac_number_2').val();

        if (number_1 == "") {
            $('#ac_number_1').css('border', '1px solid red');
            return false;
        }

        $('#ac_number_1').css('border', '1px solid #ddd');

        $.ajax({
            type: "post",
            url: subsactivations_actions.ajaxurl,
            data: {
                action: 'subsactivations_data_check',
                number_1: number_1,
                number_2: number_2,
                nonce: subsactivations_actions.nonce
            },
            beforeSend: () => {
                $('#subsactivations-subtn').prop('disabled', true).val('Activating...');
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    //Alert show
                    junu_alert_info(response.success);
                    $('#subsactivations-subtn').removeAttr('disabled').val('Activate');
                    return false;
                }
                if (response.error) {
                    //Alert show
                    junu_alert_info(response.error);
                    $('#subsactivations-subtn').removeAttr('disabled').val('Activate');
                    return false;
                }
                if (response.changed) {
                    //Alert show
                    junu_alert_info(response.changed);
                    $('#subsactivations-subtn').removeAttr('disabled').val('Activate');
                    return false;
                }
                if (response.changedboth && response.successboth) {
                    //Alert show
                    junu_alert_info(response.changedboth + '<br>' + response.successboth);
                    $('#subsactivations-subtn').removeAttr('disabled').val('Activate');
                    return false;
                }
            }
        });
    });

    // Product mtids
    $('#subsactivations-mtidsbtn').on("click", function (e) {
        btn = $(this);
        e.preventDefault();

        let data = []
        $('.mtids').each(function () {
            let id = $(this).attr('data-id');
            // if (!id) {
            //     e.preventDefault();
            //     return false;
            // }
            let product_id = $(this).attr('p-id');
            let values = $(this).val();
            data.push({
                'product_id': product_id,
                'values': values,
                'id': id
            });
        });
    
        $.ajax({
            type: "post",
            url: subsactivations_actions.ajaxurl,
            data: {
                action: 'subsactivations_mtids_store',
                data:data,
                nonce: subsactivations_actions.nonce
            },
            beforeSend: () => {
                btn.prop('disabled', true).val('Activating...');
            },
            success: function (response) {
                btn.removeAttr('disabled').val('Activate');
                location.reload();
            }
        });
    });
});