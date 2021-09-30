jQuery(function ($) {

    $('.ufields').find('.mtids').each(function () {
        if ($(this).is(':disabled')) {
            $('#subsactivations-mtidsbtn').prop('disabled', true)
        } else {
            $('#subsactivations-mtidsbtn').removeAttr('disabled')
            return false;
        }
    });

    function junu_alert_info(txt,top,time) {
        setTimeout(() => {
            $('.ufields').append('<span style="margin-top:'+top+'px;" class="alert_info">'+txt+'</span>');
            $('.alert_info').animate({'right': '15px'});
            setTimeout(() => {
                $('.alert_info').remove();
            }, 6000);
        }, time);
    }

    // Product mtids
    $('#subsactivations-mtidsbtn').on("click", function (e) {
        btn = $(this);
        e.preventDefault();

        let data = []
        $('.mtids').each(function () {
            let id = $(this).attr('data-id');
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
            dataType: 'json',
            success: function (response) {
                btn.removeAttr('disabled').val('Activate');
                
                if (response.success) {
                    //Alert show
                    let top = 0;
                    let time = 0;
                    $.each(response, function () {
                        $.each(this, function (k, v) {
                            junu_alert_info(v,top,time);
                            $('#subsactivations-subtn').removeAttr('disabled').val('Activate');
                            top += 55;
                            time += 300;
                        });
                    });
                    
                    setTimeout(() => {
                        // location.reload(); 
                    }, 1000);
                }
                
                if (response.error) {
                    //Alert show
                    junu_alert_info(response.error,'0',300);
                    $('#subsactivations-subtn').removeAttr('disabled').val('Activate');
                    return false;
                }
            }
        });
    });
});