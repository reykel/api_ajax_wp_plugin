jQuery(document).ready( function(){
    var post_type = jQuery(this).data( 'type' );

    jQuery('#data_table').show();
    jQuery('#data_details').hide();

    jQuery('#data_table').html('Loading data...');

    jQuery.ajax({
        url : aj_ajax_demo.ajax_url,
        type : 'post',
        data : {
            action : 'aj_ajax_demo_get_users',
            nonce : aj_ajax_demo.aj_demo_nonce,
            post_type : post_type,
        },
        success : function( response ) {
            jQuery('#data_table').html(response);
        },
        error : function( response ) {
            alert('Error retrieving the information: ' + response.status + ' ' + response.statusText);
            console.log(response);
        }
    });

    jQuery('#data_table').on('click','.Tlink',function(e) {
        e.preventDefault();

        var id = this.id;

        jQuery('#data_table').hide();
        jQuery('#data_details').show();

        jQuery('#data_details').html('Loading data...');

        jQuery.ajax({
            url : aj_ajax_demo.ajax_url,
            type : 'post',
            data : {
                action : 'aj_ajax_demo_get_user_details',
                nonce : aj_ajax_demo.aj_demo_nonce,
                post_type : 'post',
                valor : id,
            },
            success : function( response ) {
                jQuery('#data_details').html(response);
            },
            error : function( response ) {
                alert('Error retrieving the information: ' + response.status + ' ' + response.statusText);
                console.log(response);
            }
        });
    });

    jQuery('#data_details').on('click','#back_button',function() {
        jQuery('#data_table').show();        
        jQuery('#data_details').hide();
    });    
});

