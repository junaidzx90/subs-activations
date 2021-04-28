jQuery(function ($) {
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
                $('#subsactivations-subtn').removeAttr('disabled').val('Activate');
            }
        });
        
    })
});