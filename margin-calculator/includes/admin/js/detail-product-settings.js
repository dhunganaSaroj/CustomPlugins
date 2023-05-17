//repeating the inpput fields
jQuery(document).ready(function () {
    jQuery(document).on('click', '.product-settings-repeater', function () {
        var html = jQuery(this).closest('.settings_repeat_wrap').find('.single-product-repeater_field:last').clone();
        var child_count = jQuery(this).closest('.settings_repeat_wrap').find('.repeat_wrap .single-product-repeater_field').length;
        var price = "custom_field_repeat[" + child_count + "][price]";
        var quantity = "custom_field_repeat[" + child_count + "][quantity]";
        var special_price = "custom_field_repeat[" + child_count + "][special_price]";
        var price_value = " ";
        var quantity_value = " ";
        var special_price_value = " ";
        html.find('.variations_price_input_fields').attr('name', price);
        html.find('.variations_price_input_fields').attr('value', price_value);
        html.find('.variations_quantity_input_fields').attr('name', quantity);
        html.find('.variations_quantity_input_fields').attr('value', quantity_value);
        html.find('.variations_special_price_input_fields').attr('name', special_price);
        html.find('.variations_special_price_input_fields').attr('value', special_price_value);
        jQuery(this).closest('.settings_repeat_wrap').find('.single-product-repeater_field:last').after('<div class="single-product-repeater_field" id="single-product-repeater">' + html.html() + '</div>');
        console.log(child_count);
    });
});

// remove the repeater element
jQuery(document).ready(function () {
    jQuery(document).on('click', '.detail_productremove_row', function () {
        jQuery(this).closest('.single-product-repeater_field').remove();
    });
});

