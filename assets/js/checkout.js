(function ($) {

    $(document).ready(function () {

        var $form = $('form.checkout_cjp_discount');

        // Add click listener
        $(document.body).on('click', 'a.js-showcjp', function () {
            $('.checkout_cjp_discount').slideToggle(400);
            return false;
        });

        // Add datepicker with CJP age range
        $('#datepicker', $form).datepicker({
            changeMonth: true,
            changeYear: true,
            minDate: "-31Y",
            maxDate: "-17Y"
        });

        //  Add form submit listener
        $form.hide().submit(function () {
            if ($form.is('.processing')) {
                return false;
            }

            $form.addClass('processing').block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });

            var data = {
                security: $form.find('input[name="_wpnonce"]').val(),
                card_number: $form.find('input[name="card_number"]').val(),
                birthdate: $form.find('input[name="birthdate"]').val()
            };

            $.ajax({
                type: 'POST',
                url: wc_checkout_params.wc_ajax_url.toString().replace('%%endpoint%%', 'cjp_validate_credentials'),
                data: data,
                success: function (response) {
                    $('.woocommerce-error, .woocommerce-message').remove();
                    $form.removeClass('processing').unblock();

                    if (response) {
                        try {
                            response = JSON.parse(response);
                        } catch (e) {
                            $(document.body).trigger('update_checkout', {update_shipping_method: false});
                        }

                        if (response.success) {
                            $form.slideUp();
                            $(document.body).trigger('update_checkout', {update_shipping_method: false});
                        } else {
                            $form.before('<div class="woocommerce-message woocommerce-error">' + response.data.message + '</div>')
                        }
                    }
                },
                dataType: 'html'
            });

            return false;
        });

    });

})(jQuery);