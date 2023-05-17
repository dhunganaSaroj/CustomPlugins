// Global Settings js
jQuery(document).ready(function () {
    // global settings
    jQuery('#margin_row').click(function () {
        var html = jQuery("#margin_repeater_row").clone();
        //console.log(html);
        var child_count = jQuery('.tbl_identify tr').length - 1;
        var base_value = "my_setting_field[" + child_count + "][base_value]";
        var spend_tier = "my_setting_field[" + child_count + "][spend_tier]";
        var margin = "my_setting_field[" + child_count + "][margin]";
        var markup = "my_setting_field[" + child_count + "][markup]";
        var base_number = " ";
        var spend_value = " ";
        var margin_value = " ";
        var markup_value = " ";
        html.find('.margin_base_value').attr('name', base_value);
        html.find('.margin_base_value').attr('value', base_number);
        html.find('.margin_spend_tier').attr('name', spend_tier);
        html.find('.margin_spend_tier').attr('value', spend_value);
        html.find('.margin_margin').attr('name', margin);
        html.find('.margin_margin').attr('value', margin_value);
        html.find('.margin_markup').attr('name', markup);
        html.find('.margin_markup').attr('value', markup_value);
        // console.log(html.html());
        jQuery('.tbl_identify tr:last').after('<tr class="margin_table_row" id="margin_repeater_row">' + html.html() + '</tr>');
    });
});




jQuery(document).ready(function () {
    jQuery(document).on('click', '.remove_row', function () {
        jQuery(this).closest('#margin_repeater_row').remove();
    });
});


//Margin Markup auto Filler
jQuery(document).on('change', '.margin_margin', function () {
    var margin_value = (jQuery(this).val()) / 100;
    var markup_value = [margin_value / (1 - margin_value)] * 100;
    // console.log(markup_value.toFixed(2));
    jQuery(this).closest('#margin_repeater_row').find('.margin_markup').attr('value', markup_value.toFixed(2));
});

jQuery(document).on('change', '.margin_markup', function () {
    var markup_value = (jQuery(this).val()) / 100;
    var margin_value = [markup_value / (1 + markup_value)] * 100;
    // console.log(markup_value.toFixed(2));
    jQuery(this).closest('#margin_repeater_row').find('.margin_margin').attr('value', margin_value.toFixed(2));
});

