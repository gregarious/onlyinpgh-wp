// JavaScript Document
var CustomMenuImages = ({
	menuIDs: [],
	prefix: 'custom-menu-image',
	mediaUploadUrl: 'http://localhost/wordpress/3.1/wp-admin/media-upload.php',
	init: function(options){
		this.prefix 		= options.prefix;
		this.mediaUploadUrl = options.mediaUploadUrl;
		
		var ajaxCatcher = jQuery('<div></div>');
	    ajaxCatcher.ajaxComplete(function(event, xhr, options){
			if(options.data.indexOf('add-menu-item')!=-1)										  
				CustomMenuImages.loadCustomFields();
		});	
		return this;
	},						
	addImage: function(el, id){
		var sizes = jQuery("td.field input");
		var size = '';
		for(var i = 0;i <sizes.length;i++){
			if(sizes[i].name == 'attachments['+jQuery(el).attr('rel')+'][image-size]'){
				if(sizes[i].checked){
					size = (sizes[i].value)
					break;
				}
			}
		}
        var data = {
            cmi_id: id,
            action: 'add_image',
            attachment_id : jQuery(el).attr('rel'),
			size: size
        }
        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: data,
            dataType: 'json',
            cache: false,
            success: function (data, textStatus ) {
               
                /* Vars */
                data = eval( data );
				
                var prev_id = CustomMenuImages.prefix+'-preview-'+data.cmi_id;	
				
				//alert(prev_id);
                jQuery( parent.document.getElementById( prev_id ) )
                .css({display: 'block'})
                .attr("src", data.thumb)
                
                var ml_id = CustomMenuImages.prefix+'-ml-'+data.cmi_id;			
                /* Refresh the image on the screen below */
                jQuery( parent.document.getElementById( ml_id ) )
                .val(data.thumb)

                /* Close Thickbox */
                self.parent.tb_remove();
            }
        });
	},
	removeImage: function(el){
		var data = {
            cmi_id: jQuery(el).attr("rel"),
            action: 'remove_image',
        }
        jQuery.ajax({
            url: ajaxurl,
            type: "POST",
            data: data,
            dataType: 'json',
            cache: false,
            success: function (data, textStatus ) {
                data = eval( data );
                var prev_id = CustomMenuImages.prefix+'-preview-'+data.cmi_id;	
                jQuery( parent.document.getElementById( prev_id ) )
                .css({display: 'none'})
                .attr("src", "")
                
                var ml_id = CustomMenuImages.prefix+'-ml-'+data.cmi_id;			
                /* Refresh the image on the screen below */
                jQuery( parent.document.getElementById( ml_id ) )
                .val("")
            }
        });	
	},
	loadCustomFields: function(){
		var p = jQuery("div.menu-item-settings");
			
		//var url = '<?php echo admin_url( 'media-upload.php' )?>';
		
		for(var i = 0; i < p.length; i++){
			var id = p[i].id.substr(19);		
			if(this.menuIDs.indexOf("#"+id)!=-1) continue;
			this.menuIDs.push('#'+id);
			var sibling = jQuery("#edit-menu-item-attr-title-"+id).parent().parent();
			var checked = (menu_item_options[id] ? (menu_item_options[id].url_type == 'lib' ? 'lib' : 'url') : 'url');
			var radioName = this.prefix+'-url-type['+id+']';
			var imageUrl = (menu_item_options[id] ? menu_item_options[id].url : "");
			var imageLib = (menu_item_options[id] ? menu_item_options[id].media_lib : "");
			var str_e = '\
					<'+'fieldset style="border:1px solid #CCCCCC;padding: 3px;">\
					<'+'legend style="margin-left:5px;">Navigation Image</'+'legend>\
						<'+'p class="description description-thin" style="height:auto;">\
						<'+'input type="radio" name="'+radioName+'" value="url" '+(checked=='url' ? 'checked="checked"' : '')+' />From URL\
						<'+'input type="text" value="'+imageUrl+'" name="'+this.prefix+'-url['+id+']" class="widefat edit-menu-item-title" id="edit-menu-item-title-'+id+'">\
						<'+'br />\
						<'+'input type="radio" name="'+radioName+'" value="lib" '+(checked!='url' ? 'checked="checked"' : '')+'/>From Media Library\
						<'+'br />\
						<'+'img src="'+imageLib+'" id="'+this.prefix+'-preview-'+id+'" style="max-width:150px;max-height:150px;" />\
						<'+'input type="hidden" name="'+this.prefix+'-ml['+id+']" id="'+this.prefix+'-ml-'+id+'" value="'+imageLib+'" />\
						<'+'br />\
						<'+'a class="add-image-'+id+'" href="'+this.mediaUploadUrl+'?type=image&amp;tab=library&amp;cmi_id='+id+'&amp;TB_iframe=true" onclick="return false;">Add</'+'a>\
						<'+'a class="remove-image" href="javascript:void(0);" onclick="CustomMenuImages.removeImage(this);" rel="'+id+'">Remove</'+'a>\
						<'+'div style="clear:both;"></'+'div>\
					<'+'/p>\
					<'+'/fieldset>';
			var new_e = jQuery(str_e);		
			sibling.after(new_e);
			if(imageLib == '') jQuery('#'+this.prefix+'-preview-'+id).css({display: 'none'});
			tb_init("a.add-image-"+id);
		}
		//tb_init('a.add-images');//pass where to apply thickbox
	}
})