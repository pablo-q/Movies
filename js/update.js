$( document ).ready(function() {
	jQuery("#paisSelect").val( jQuery("#pais").val() ).change();
});

function savePaisId(){
	if(jQuery("#paisSelect").val()!==undefined){
		jQuery("#pais").val(jQuery("#paisSelect").val());
	}
	if(jQuery("#directorSelect").val()!==undefined){
		jQuery("#director").val(jQuery("#directorSelect").val());
	}
	if(jQuery("#generoSelect").val()!==undefined){
		jQuery("#genero").val(jQuery("#generoSelect").val());
	}
	if(jQuery("#actorSelect").val()!==undefined){
		jQuery("#actores").val(jQuery("#actorSelect").val());
	}
}

function playVideo(id){
	jQuery("#clickMe"+id).attr('src', jQuery("#clickMe"+id).attr('src')+'?autoplay=1');
}