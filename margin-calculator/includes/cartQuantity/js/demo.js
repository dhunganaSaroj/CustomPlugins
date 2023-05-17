jQuery(document).ready(function () {
    jQuery(document).on("click", '.selected-quantity', function () {
        var checked_value = jQuery('.selected-quantity:checked').val();
        var sanitized = $.trim(checked_value);
        // console.log(sanitized);
        jQuery('.input-qty').val(sanitized);
    });

    jQuery(document).on("click", '.selected-quantity', function () {
        var checked_value = jQuery('.selected-quantity:checked').data('price');
        var discounted_value = jQuery('.selected-quantity:checked').data('discount');
        var max_price = jQuery('.selected-quantity:checked').data('maxprice');
        jQuery('.dat-wooprice').text(checked_value);
        jQuery('.you-save').text('You Save ' + discounted_value + ' from $' + max_price);
    });

    jQuery('.btn-calculate').click(function () {
        var checked_value = jQuery('.input-qty').val();
        var prc_value = jQuery('.dat-wooprice').text()
        var total_value = (checked_value * prc_value);
        $('.woo-price-total').text('$' + total_value.toFixed(2));
    });
});

