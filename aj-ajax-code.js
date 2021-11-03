jQuery(document).ready( function(){
    jQuery('#str_criteria').on('keyup', function(e) {
        var post_type = jQuery(this).data( 'type' );

        jQuery('#data_table').html('?');

        e.preventDefault();

        jQuery.ajax({
            url : aj_ajax_demo.ajax_url,
            type : 'post',
            data : {
                action : 'aj_ajax_demo_get_cocktails',
                nonce : aj_ajax_demo.aj_demo_nonce,
                post_type : post_type,
                valor : jQuery('#str_criteria').val(),
                contenido : jQuery('#select').val()
            },
            success : function( response ) {
                jQuery('#data_table').html(response);
            },
            error : function( response ) {
                alert('Error retrieving the information: ' + response.status + ' ' + response.statusText);
                console.log(response);
            }
        });
    });

    $("#select").change(function(){
        jQuery('#str_criteria').val('');
        jQuery('#data_table').html('');
    });
});