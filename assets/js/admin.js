
jQuery(document).ready(function (jQuery) {
    //
    // Add new options to select, checkbox form element. The js will ad one more line for value and label
    //
    jQuery(document).on('click', '.bf_add_conditional_logic', function () {

        var conditional_logic_button = jQuery(this);
        var args = conditional_logic_button.attr('href').split("/");
        var field_id = args[0];
        var form_slug = conditional_logic_button.attr('data-form_slug');
        var numItems = jQuery('#table_row_' + args[0] + '_select_options ul li').size();


        jQuery.ajax({
            type: 'POST',
            dataType: "json",
            url: ajaxurl,
            data: {
                "action": "buddyforms_add_conditional_logic",
                "field_id": field_id,
                "numItems": numItems,
                "form_slug": form_slug
            },
            success: function (data) {

                console.log(data);

                numItems = numItems + 1;
                jQuery('#table_row_' + args[0] + '_select_options ul').append(data);

            }
        });
        return false;
    });
});
