jQuery(function ($) {
    $('#rest_color').on('click', function (e) {
        e.preventDefault();
       $.ajax({
           type: "post",
           url: admin_ajax_action.ajaxurl,
           data: {
               action: 'subsactivations_reset_colors'
           },
           success: function (response) {
               location.reload();
           }
       }); 
    });
});