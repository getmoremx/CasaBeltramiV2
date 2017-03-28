var meta = new Object();
meta.save = function( res , box , post_id , selector ){
	var selected_box_id = jQuery('.'+box+'-idrecord option:selected').val(); 
	
	/*for some meta we need to check if user has selected from provided select a real value
	 In the array bellow add those meta  to be checked:
	*/
	var exceptions_meta_arr = [ "conference","presentation","exhibitor", "sponsor" ,"speaker" ];
	
	/*check if exceptions_meta_arr  contains  'box' */
	var is_exception_meta = jQuery.inArray( box, exceptions_meta_arr );
	if (selected_box_id > 0 || is_exception_meta == -1){
		jQuery( document ).ready(function(){
			jQuery( 'div#form' + box ).wrap( '<form id="wrapform' + box + '" />' );
			var data = jQuery( 'form#wrapform' + box ).serializeArray( );
			jQuery( 'div#form' + box ).unwrap( 'form#wrapform' + box );
			jQuery.post(
			ajaxurl , {
			"action" : 'meta_save' ,
			"res" : res,
			"box" : box,
			"post_id" : post_id,
			"data" : data }, function( result ){
					jQuery( selector).addClass('postbox');
					jQuery( selector ).html( result );
			} );
		});
	}else{
		alert('Please select '+box);
	}  
}

meta.save_data = function( res , box , post_id , data , selector ){

    if ( post_id > 0 ){
        jQuery( document ).ready(function(){
            jQuery.post( ajaxurl , { "action" : 'meta_save' , "res" : res , "box" : box , "post_id" : post_id , "data" : data } ,
                function( result ){
                    if( selector != '.--'){
                        jQuery('#attach_'+box+'_'+res).fadeTo( 100 , 1 );
                        jQuery('#attach_'+box+'_'+res).fadeTo( 2000 , 0 );
                        jQuery.post( ajaxurl , { "action" : "search_relation" , "post_id" : data[0].value , "args" : [ res , box ] } , function( result ){ jQuery( selector).addClass('postbox'); jQuery( selector ).html( result ); });
                    }
                }
            );
        });
    }else{
        if( res == 'presentation' && box == 'speaker' ){
            alert('Please select ' + ' conferance and presentations' );
        }else{
            alert('Please select ' + res );
        }
    }
}

meta.del = function( res ,  box , post_id , index  , selector ){
	jQuery( document ).ready(function(){
		jQuery.post(
		ajaxurl , {
		"action" : 'meta_delete' ,
        "res" : res,
		"box" : box,
		"post_id" : post_id,
		"index" : index }, function( result ){
            if( result.length > 0 ){
                jQuery( selector).addClass('postbox');
            }else{
                jQuery( selector).removeClass('postbox');
            }
			jQuery( selector ).html( result );
		} );
	});
}

meta.del_data = function( res ,  box , post_id , index , post_id_result , selector ){
	jQuery( document ).ready(function(){
		jQuery.post(
		ajaxurl , {
		"action" : 'meta_delete' ,
        "res" : res,
		"box" : box,
		"post_id" : post_id,
		"index" : index }, function( result ){
            if( selector != '.--' ){
                jQuery.post( ajaxurl , { "action" : "search_relation" , "post_id" : post_id_result , "args" : [ res , box ] } ,
                    function( result ){
                        if( result.length > 0 ){
                            jQuery( selector).addClass('postbox');
                        }else{
                            jQuery( selector).removeClass('postbox');
                        }

                        jQuery( selector ).html( result );

                    }
                );
            }
		});
	});
}

meta.clear = function( selector ){
    jQuery( document ).ready(function(){
        jQuery('input[type="radion"]' + selector ).attr('checked','unchecked');
        jQuery('select' + selector ).attr('selected','unselected');
        jQuery('textarea' + selector ).val('');
        jQuery('input' + selector ).val('');
    });
}

meta.sort = function( res , box , post_id , name ){
    var data = new Array();
    jQuery( document ).ready(function(){
        jQuery( 'input.' + res + '-' + box + '-' + name ).each(function( i ){
            data[i] = { 'name' : name + '[]' , 'value' : jQuery( this ).val() };
        });

        jQuery.post( ajaxurl , { "action" : "meta_sort" , "res" : res , "box" : box , "post_id" : post_id , "data" : data } ,
                    function( result ){
                        jQuery( 'div.layout-a.meta-box.sort-' + res + '-' + box ).html( result );
                    }
                );
    });
}

meta.update = function( res , box , post_id , struct , index , selector ){
    var data = new Array();
    var k = 0;
    for (var key in struct ) {
        if ( struct.hasOwnProperty( key ) ) {
            if( ( typeof struct[ key ] ).toString() != "string" ){
                for( var i = 0; i < struct[ key ].length; i++ ){
                    data[ k ] = { 'name' : extra.name( 'div.meta-' + box + '-' + index + ' .' + struct[ key ][i] ) , 'value' : extra.val( 'div.meta-' + box + '-' + index + ' .' + struct[ key ][i] ) }
                    k++;
                }
            }else{
                data[ k ] = { 'name' : extra.name( 'div.meta-' + box + '-' + index + ' .' + struct[ key ] ) , 'value' : extra.val( 'div.meta-' + box + '-' + index + ' .' + struct[ key ] ) }
                k++;
            }
        }
    }
    jQuery( document ).ready(function(){
        jQuery.post( ajaxurl , { "action" : 'meta_update' , "res" : res , "box": box , "post_id" : post_id , "data" : data , "index" : index } , function( result ){ jQuery( 'div.layout-a.meta-box.sort-' + res + '-' + box ).html( result ); } );
    });
}

meta.edit = function( res , box , post_id , index , selector ){
    jQuery( document ).ready(function(){
        jQuery( 'div.meta-' + box + '-' + index + ' .edit-action').hide();
        jQuery( 'div.meta-' + box + '-' + index + ' .update-action').show();
        jQuery( 'div.meta-' + box + '-' + index + ' .fvisible' ).show();
        jQuery( 'div.meta-' + box + '-' + index + ' .lvisible' ).hide();
        init_color_pickers( '.generic-meta-color-picker' );
    });
}

jQuery(document).ready(function($){


    //hide/show the meta box for portfolio template settings
    function portfolioDisplaySettings(){
        if(jQuery('select#page_template').val() == 'latest-portfolios.php'){
            jQuery('#page_portfolioTemplateSettings').show();
        } else {
            jQuery('#page_portfolioTemplateSettings').hide();
        }
    }

    jQuery('select#page_template').change(portfolioDisplaySettings);
    portfolioDisplaySettings();


    //hide/show the meta box for gallery sortable template settings
    function galleryDisplaySettings(){
        if(jQuery('select#page_template').val() == 'latest-galleries.php'){
            jQuery('#page_galleryTemplateSettings').show();
        } else {
            jQuery('#page_galleryTemplateSettings').hide();
        }
    }

    jQuery('select#page_template').change(galleryDisplaySettings);
    galleryDisplaySettings();

    function templateDisplaySettings(){
        if (jQuery('select#page_template').val() == 'template-page.php') {
            jQuery('#page_settings').show();
            jQuery('#page_layout').hide();
        }else if( jQuery('select#page_template').val() == 'default' ){
            jQuery('#page_settings').show();
            jQuery('#page_layout').show();
        }else{
            jQuery('#page_settings').hide();
            jQuery('#page_layout').hide();
        }
    }

    jQuery('select#page_template').change(templateDisplaySettings);
    templateDisplaySettings();

    /**
     * Hide attached image manager for non gallery post formats
     */
    jQuery(document).ready(function(){
        if( jQuery('.post-format:checked').attr('value') == 'gallery' ){
            jQuery('#lala-post-images-post').show();
        } else{
            jQuery('#lala-post-images-post').hide();
        }
    });
    jQuery('.post-format').click(function(event) {
        if( jQuery(this).attr('value') == 'gallery' ){
            jQuery('#lala-post-images-post').show();
        } else{
            jQuery('#lala-post-images-post').hide();
        }
    });
    /**
     * Hide video embed for non video post formats
     */
    $(document).ready(function(){
        if( $('.post-format:checked').attr('value') == 'video' || $('.post-format:checked').attr('value') == 'audio' ){
            jQuery('#post_redembed').show();
        } else{
            jQuery('#post_redembed').hide();
        }
    });
    $('.post-format').click(function(event) {
        if( $(this).attr('value') == 'video' || $('.post-format:checked').attr('value') == 'audio' ){
            jQuery('#post_redembed').show();
        } else{
            jQuery('#post_redembed').hide();
        }
    });
});

