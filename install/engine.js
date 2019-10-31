
$(document).ready(function(){
	if($('input[name=base_url]').attr('value')==''){
		$('input[name=base_url]').attr('value',window.location.origin);
	}
	$("#form_install").submit( function () {   
		$('.red').css({'display':'none'});
		if($('input[name=password_cms]').val()!=$('input[name=password_cms_repeat]').val()){
			$('.red').css({'display':'block'});
			return false;
		}
    });  
});

