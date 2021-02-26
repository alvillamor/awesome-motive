jQuery(function ($) {
    
    var data = {
        action: 'print_data_ajax'
    };

    $.ajax({
        url: am_challenge_script.ajax_url,
        type: 'POST',
        data: data,
        success: function (response) {
            if (response) {
                $('#am-challenge-data').html(response);
            }
        }
    });

});
