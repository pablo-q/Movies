jQuery(document).ready(function() {
     optionss = [];
        jQuery('#actorSelect').multiselect({
            buttonText: function(options, select) {
                
            	if (options.length === 0) {
                    return 'Seleccione Actores';
                }else {
                     var labels = [];
                     options.each(function() {
                         if (jQuery(this).attr('label') !== undefined) {
                             labels.push(jQuery(this).attr('label'));
                         }
                         else {
                             labels.push(jQuery(this).html());
                         }
                     });
                     return labels.join(', ') + '';
                 }
            }
        });
        jQuery(".multiselect.dropdown-toggle.btn.btn-default").attr('title', jQuery("#actoresSelNom").val());
        jQuery("button").find('span').html(jQuery("#actoresSelNom").val());
        jQuery("ul").find('li').each( function(){
            if( jQuery(this).find('label').html().split("> ")[1] !== undefined && jQuery.inArray(jQuery(this).find('label').html().split("> ")[1], jQuery("#actoresSelNom").val().split(", ")) !== -1 ){
                jQuery(this).attr("class", "active");
                jQuery(this).find('input').prop('checked', true);;
            }
        });
        var ids = '';
        ids = jQuery("#actorSelect").val();
        jQuery("#actorSelect").find('option').each( function(){
            if( jQuery(this).html() !== undefined && jQuery.inArray( jQuery(this).html(), jQuery("#actoresSelNom").val().split(", ")) !== -1){                
                optionss.push(jQuery(this)[0]);
                if(ids !== null && ids !== undefined){
                    ids.push(jQuery(this).val());
                }else{
                    ids = [jQuery(this).val()];
                }
            }
        });
        jQuery("#actorSelect").val(ids).change();
        jQuery("button").attr('title', jQuery("#actoresSelNom").val());
        jQuery("#paisSelect").val( jQuery("#pais").val() ).change();
        jQuery("#directorSelect").val( jQuery("#director").val() ).change();
});

function saveSelectId(){
	jQuery("#pais").val(jQuery("#paisSelect").val());
	jQuery("#genero").val(jQuery("#generoSelect").val());
	jQuery("#director").val(jQuery("#directorSelect").val());
	jQuery("#actores").val(jQuery("#actorSelect").val());
}