$(document).ready(function(){
    
	function generate_plot() {
		var ret = null;
		$.ajax({
			type: 'POST', 
			url: 'php/statistics_ajax.php',
			dataType :"json",
			data: {'select_1': $('#statitics_select_1').val(),'select_2': $('#statitics_select_2').val(), 'date_from': $('#statistics_date_from').val(), 'date_to': $('#statistics_date_to').val()},
			success: function(data) {
				ret = data;
				$('#statistics_generate').removeAttr('disabled');
				$.jqplot('jqplot', data, CreateBarChartOptions()).replot();
			}
		});
	};
	$('#statistics_generate').click(function(){
		$(this).attr('disabled','disabled');
		generate_plot();
	});
	$('#statistics_generate').click();
	
	function CreateBarChartOptions(xAxis) {
		var optionsObj = {
			axes:{
				xaxis:{
					renderer:$.jqplot.DateAxisRenderer,
					tickOptions:{
						formatString: '%d-%m-%Y'
					}
				},
				yaxis: {              
					min: 0
				}
			},
			highlighter: {
				show: true,
				sizeAdjust: 7.5
			},
			cursor: {
				show: false
			}
		};
		return optionsObj;
	}
});