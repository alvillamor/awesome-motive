jQuery(function ($) {


    $(".am-challenge-submit-btn").click(function(e) {
        e.preventDefault();
        let $button = $(this);
        
        $button.attr('disabled', 'disabled');

        var data = {
            action: 'refresh_data_ajax'
        };

        $.ajax({
            url: am_challenge_admin_script.ajax_url,
            type: 'POST',
            data: data,
            success: function (response) {
                if (response) {
                    $('#am-challenge-data').html(response);
                    $button.removeAttr('disabled');
                }
            }
        });
    });

});
