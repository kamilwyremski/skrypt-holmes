$(document).ready(function(){
	
	$('.inactive').click(function(){
		return false;
	})
	
	$(".ajax").not('.inactive').click(function(){
		var mydata = $(this).data();
		$.post('php/function_ajax.php', {
			'data' : mydata,
			'send': 'ok'}, 
			function(data) {
				window.location.href = window.location;
		});
        return false;   
    });
	
	$('#menu').slimmenu({resizeWidth: '750',collapserTitle: 'Menu',animSpeed:'medium',indentChildren: true});
	
	$(".dialog").dialog({
		modal: true,
		open: function() {
			$(this).dialog("option", "title", $(this).data('title'));
		},
		autoOpen:false,
		buttons: [{
			text: "OK",
			click: function(){$(this).find("[type=submit]").click()},
			type: "submit"
		},{
			text: "CANCEL",
			click: function() {
				$(this).find("[type=reset]").click()
				$(this).dialog( "close" );
			}
		}]
	});
	
	$('.open_dialog').click(function(){
		$($(this).attr('href')).dialog('open');
		return false;
	})
	
	$('.set_required').click(function(){
		$this = $(this);
		$target = $('.'+$this.data('target'));
		if ($this.is(':checked')) {
			$target.prop('required', true);
		}else{
			$target.prop('required', false);
		}
	}) 
	$('.set_required').each(function(){
		$this = $(this);
		$target = $('.'+$this.data('target'));
		if ($this.is(':checked')) {
			$target.prop('required', true);
		}else{
			$target.prop('required', false);
		}
	})

	$.datepicker.regional['pl'] = {
        closeText: 'Zamknij',
        prevText: '&#x3c;Poprzedni',
        nextText: 'Następny&#x3e;',
        currentText: 'Dziś',
        monthNames: ['Styczeń','Luty','Marzec','Kwiecień','Maj','Czerwiec',
        'Lipiec','Sierpień','Wrzesień','Październik','Listopad','Grudzień'],
        monthNamesShort: ['Sty','Lu','Mar','Kw','Maj','Cze',
        'Lip','Sie','Wrz','Pa','Lis','Gru'],
        dayNames: ['Niedziela','Poniedziałek','Wtorek','Środa','Czwartek','Piątek','Sobota'],
        dayNamesShort: ['Nie','Pn','Wt','Śr','Czw','Pt','So'],
        dayNamesMin: ['N','Pn','Wt','Śr','Cz','Pt','So'],
        weekHeader: 'Tydz',
        dateFormat: 'yy-mm-dd',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''
	};
	if(lang=='pl'){
		$.datepicker.setDefaults($.datepicker.regional['pl']);
	}
	$('input[type=date]').each( function() {
        var $this = $(this);
		var minDate = $this.attr('min'), maxDate = $this.attr('max');
        $this.datepicker({minDate:minDate,maxDate:maxDate}); 
    });
	
	$('.select_checkbox').click(function(){
		$this = $(this);
		if ($this.is(':checked')) {
			$this.parents('.parent_select_checkbox').find('input[type=checkbox]').prop('checked', true);
		}else{
			$this.parents('.parent_select_checkbox').find('input[type=checkbox]').prop('checked', false);
		}
	}) 
	
	$('.offers_option_select').change(function(){
		$this = $(this);
		if($this.val()=='select'){
			$this.parents('form').find('.offers_option_label').show().find('input[type=text]').attr("disabled", false);
		}else{
			$this.parents('form').find('.offers_option_label').hide().find('input[type=text]').attr("disabled", true);
		}
	})
	$('.offers_option_select').change();
	
	$('.offers_option_all_type').click(function(){
		$this = $(this);
		if ($this.is(':checked')) {
			$this.parents('form').find('.offers_option_all_type_div').slideToggle().find('input[type=checkbox]').prop('checked', true);
		}else{
			$this.parents('form').find('.offers_option_all_type_div').slideToggle().find('input[type=checkbox]').prop('checked', false);
		}
	}) 
	
})

$(document).on('click', '.open_roxy', function(){
	$('.roxy_target').removeClass('roxy_target');
	$(this).find('img').addClass('roxy_target');
	$('#roxyCustomPanel').dialog({modal:true, width:875,height:600});
	return false;
})
	
function closeCustomRoxy(){
	$roxy_target = $('.roxy_target');
	$("[name='"+$roxy_target.data('roxy_name')+"']").val(base_url+$roxy_target.attr('src'));
	$('#roxyCustomPanel').dialog('close');
}
