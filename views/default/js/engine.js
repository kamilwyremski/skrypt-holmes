$(document).ready(function(){
	
	$('.checkbox_select_type').change(function(){
		var $this = $(this), id = $this.data('id');
		if($this.is(':checked')){
			$('.offers_option_'+id).show().find(':input').attr("disabled", false);
		}else{
			$('.offers_option_'+id).hide().find(':input').attr("disabled", true);
		}
	})
	$('.checkbox_select_type:checked').change();
	
	$('.checkbox_select_type_all').change(function(){
		if($(this).is(':checked')){
			$('.offers_option_div').show().find(':input').attr("disabled", false);
		}else{
			$('.offers_option_div').hide().find(':input').attr("disabled", true);
		}
	})
	$('.checkbox_select_type_all:checked').change();
	
	$('.select_select_type').change(function(){
		var $this = $(this), val = $this.val();
		$('.offers_option_div').hide().find(':input').attr("disabled", true);
		$('.offers_option_'+val).show().find(':input').attr("disabled", false);
	})
	$('.select_select_type').change();
	
	$(".ajax_confirm").click(function(){
		$this = $(this);
		var is_confirmed = confirm($this.data('title'));
		if (is_confirmed) {
			var mydata = $this.data();
			$.ajax({
				url: "php/functions_ajax.php",
				type: "POST", 
				data:mydata,
				async: false,
				success: function(data) {	
					window.location.href = window.location;
				}
			});
		}
        return false;   
    });
	
	$("#button_get_coordinates").click(function(){		
		var address = $('input[name=address]').val();
		if($('[name=state]').val()){
			address += ' '+$('[name=state] option:selected').html();
		}
		if(address){
			$.ajax({
				url: "php/functions_ajax.php",
				type: "POST", 
				data:{
					'action' : 'get_coordinates',
					'address' : address
				},
				dataType :"json",
				success: function(data) {	
					if(data['lat'] && data['long']){
						var latlng = new google.maps.LatLng(data['lat'], data['long']);
						google_maps_marker.setPosition(latlng);
						google_maps.setCenter(latlng);
						$('input[name=address_lat]').val(data['lat']);
						$('input[name=address_long]').val(data['long']);
					}
				}
			});
		}
        return false;   
    });
	
	$('.selectpicker').selectpicker();

	$('.check_all').click(function(){
		$this = $(this);
		if ($this.is(':checked')) {
			$this.parents('.check_all_parent').find('.group_checkbox').slideUp().find('input[type=checkbox]').prop('checked', true).prop( "disabled",true);
		}else{
			$this.parents('.check_all_parent').find('.group_checkbox').slideDown().find('input[type=checkbox]').prop('checked', false).prop( "disabled",false);
		}
	}) 
	$('.check_all').each(function(){
		$this = $(this);
		if ($this.is(':checked')) {
			$this.parents('.check_all_parent').find('.group_checkbox input[type=checkbox]').prop('checked', true).prop( "disabled",true);
		}
	}) 
	
	$('.show_group_checkbox').click(function(){
		$this = $(this);
		$this.parents('.check_all_parent').find('.group_checkbox').slideDown().find('input[type=checkbox]').prop( "disabled",false);
		$this.remove();
		return false;
	})
	
	$('.form-search').submit(function(){
		$form = $(this), $address = $form.find(':input[name="address"]');			
		if(!$address.length || $address.val()==''){
			$form.find(':input[name="distance"]').prop( "disabled",true);
		}
		$form.find(':input:visible').each(function(){
			$this = $(this);
			if($this.val()==''){
				$this.prop( "disabled",true);
			}
		})
	})
	
	var $menu = $('#menu_box'), $top = $('#top');
	
	function scroll() {
		var window_scrolltop = $(window).scrollTop(), top_height = $top.height() + 8;
		if (window_scrolltop >= top_height) {
			$menu.css('top',0);
		} else {
			$menu.css('top',top_height - window_scrolltop);
		}
		if(window_scrolltop>150){      		
			$('#back_to_top').removeClass('back_to_top_hidden');
		}else{
			$('#back_to_top').addClass('back_to_top_hidden');
		}
	}
	scroll();
	document.onscroll = scroll;
	window.onresize = scroll;
	
	$('.return_false a').click(function(){
		$(this).blur();
		return false;
	})
	
	$('#back_to_top').on("click", function(){
		$('html, body').stop().animate({scrollTop: 0}, 300);
		$(this).blur();
		return false;
	})
	
	$('.show_hidden_data').on("click", function(){
		var $this = $(this), $parent = $this.parents('a'), type = $this.data('type'),	data = href = $this.data('data');
		if(type=='phone'){
			href = 'tel:'+data;
		}else if(type=='email'){
			href = 'mailto:'+data;
		}
		$this.text(data).contents().unwrap();
		$parent.attr("href", href);
		return false;
	})

	$("#input_select_photo").change(function (){
		$this = $(this);
		var number_photos = $this[0].files.length;
		var photo_count = $('#preview_photos .img-thumbnail').length;
		if(number_photos && (!photo_max || photo_max+1>photo_count)){
			$('#preview_load').removeClass('hidden');
			$('#photos_info').addClass('hidden').find('span').html('');
			$('#box_add_offer input[type=submit]').prop("disabled", true); 
			$.each($this[0].files, function(index, value){
				data_photo =  new FormData();
				data_photo.append('count_photo', photo_count);
				data_photo.append('file', $this[0].files[index]);
				$.ajax({
					url: "php/photos_ajax.php",
					type: "POST", 
					data: data_photo,
					dataType :"json",
					contentType: false,
					cache: false,
					processData:false,
					success: function(data){
						if(data){
							if(data['status']){
								$("#preview_photos").append('<div class="img-thumbnail"><img src="upload/photos/'+data['thumb']+'" alt="'+data['url']+'"><br><a href="#" title="'+data['remove_title']+'" class="remove_photo">'+data['remove']+'</a><input type="hidden" name="photos[]" value="'+data['id']+'"></div>');
							}else{
								$('#photos_info').removeClass('hidden').find('span').html(data['info']);
							}
						}
						if(index===(number_photos-1)){
							$('#preview_load').addClass('hidden');
							$('#box_add_offer input[type=submit]').prop("disabled", false); 
						}
					},
					error: function (request, status, error) {
						$('#preview_load').addClass('hidden');
						$('#box_add_offer input[type=submit]').prop("disabled", false); 
					}
				});
				photo_count++;
			});
		}
		$this.val('');
	})
	
	$("#facebook2_2").hover(function(){$(this).stop(true,false).animate({right: "0px"}, 500 );},
		function(){$(this).stop(true,false).animate({right: "-300px"}, 500 );});
		
	if (window.location.href.indexOf('#_=_') > 0) {
		window.location = window.location.href.replace(/#.*/, '');
	}
	
	var doAnimations = function() {
		var offset = $(window).scrollTop() + $(window).height(),$animatables = $('.animatable');
		if ($animatables.length == 0) {
			$(window).off('scroll', doAnimations);
		}
		$animatables.each(function(i) {
			var $animatable = $(this);
			if (($animatable.offset().top + 80) < offset) {
				$animatable.removeClass('animatable').addClass('animated');
			}
		});
	};

	$(window).on('scroll', doAnimations);
	$(window).trigger('scroll');
	
	if(!localStorage.rodo_accepted) {
		$("#rodo-message").modal('show');
	}
});

function closeRodoWindow(){
	localStorage.rodo_accepted = true;
	$("#rodo-message").modal('hide');
}

$(document).on('click', '.remove_photo', function(){
	$(this).parents('.img-thumbnail').remove();
	$('#photos_info').addClass('hidden').find('span').html('');
	return false;
})

$(window).on("load", function(){
	var $js_scroll_page = $('#js_scroll_page')
  	if($js_scroll_page.length>0){
		position = $js_scroll_page.offset().top;	
		if($(window).scrollTop()+$(window).height()<position){
			$('html, body').stop().animate({scrollTop: (position-110)}, 300);
		}
	}
});