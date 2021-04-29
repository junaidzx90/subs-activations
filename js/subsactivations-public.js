jQuery(function ($) {

    function junu_alert_info(txt) {
        $('.alert_info').remove();
        $('.ufields').append('<span class="alert_info">'+txt+'</span>');
        $('.alert_info').animate({'right': '15px'});
        setTimeout(() => {
            $('.alert_info').remove();
        }, 6000);
    }

    $('#subsactivations').submit(function (e) {
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
                if (response.error){
                    //Alert show
                    junu_alert_info(response.error);
                    $('#subsactivations-subtn').removeAttr('disabled').val('Activate');
                    return false;
                }
                if (response.changed){
                    //Alert show
                    junu_alert_info(response.changed);
                    $('#subsactivations-subtn').removeAttr('disabled').val('Activate');
                    return false;
                }
                if (response.changedboth && response.successboth){
                    //Alert show
                    junu_alert_info(response.changedboth+'<br>'+response.successboth);
                    $('#subsactivations-subtn').removeAttr('disabled').val('Activate');
                    return false;
                }
            }
        });
    })
});