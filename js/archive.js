<?php foreach ($returned as $key => $ret) : ?>
				var chartForce_<?php echo $ret["phoneNumber"] ?>; // global
				var chartTemperature_<?php echo $ret["phoneNumber"] ?>; // global
				var chartHumid_<?php echo $ret["phoneNumber"] ?>; // global
			<?php endforeach ?>	
			var addGMT = 4;
			
			var maxForce= 400;
			var minForce;
			
			var date_begin;
			var date_end;
			
			
			function getData(date_to_get_begin, date_to_get_end) {						
				$.ajax({
					url: 'return-json.php', //запрос всех данных за определенную дату
					dataType: 'json',
					data: ({date_begin:date_to_get_begin.toString(), date_end:date_to_get_end.toString()}),
					success: function (hash_table) {
								var my_data = eval(hash_table);	
								
								if (my_data != undefined) {
									<?php foreach ($returned as $key => $ret) : ?>
									
										var F0_<?php echo $ret["phoneNumber"] ?> = chartForce_<?php echo $ret["phoneNumber"] ?>.series[0];
										var F1_<?php echo $ret["phoneNumber"] ?> = chartForce_<?php echo $ret["phoneNumber"] ?>.series[1];
										var F2_<?php echo $ret["phoneNumber"] ?> = chartForce_<?php echo $ret["phoneNumber"] ?>.series[2];														
									
										var T0_<?php echo $ret["phoneNumber"] ?> = chartTemperature_<?php echo $ret["phoneNumber"] ?>.series[0];
										var T1_<?php echo $ret["phoneNumber"] ?> = chartTemperature_<?php echo $ret["phoneNumber"] ?>.series[1];
										var T2_<?php echo $ret["phoneNumber"] ?> = chartTemperature_<?php echo $ret["phoneNumber"] ?>.series[2];
									
										var H0_<?php echo $ret["phoneNumber"] ?> = chartHumid_<?php echo $ret["phoneNumber"] ?>.series[0];
									
										var shift = chartForce_<?php echo $ret["phoneNumber"] ?>.series[0].data.length > 2000; 
									
										var point = [];
										
										for (var i = 0; i < my_data.date.length; i++)
										{
											if (my_data.phoneNumber[i] == "<?php echo $ret["phoneNumber"] ?>") {
												point[0] = my_data.date[i] + addGMT * 60 * 60 * 1000;
												point[1] = my_data.F0[i];
																
												F0_<?php echo $ret["phoneNumber"] ?>.addPoint(point, true, shift);						
												
												point[1] = my_data.F1[i];
												F1_<?php echo $ret["phoneNumber"] ?>.addPoint(point, true, shift);						
												
												point[1] = my_data.F2[i];
												F2_<?php echo $ret["phoneNumber"] ?>.addPoint(point, true, shift);	
												
												point[1] = my_data.T0[i];
												T0_<?php echo $ret["phoneNumber"] ?>.addPoint(point, true, shift);
												
												point[1] = my_data.T1[i];
												T1_<?php echo $ret["phoneNumber"] ?>.addPoint(point, true, shift);
												
												point[1] = my_data.T2[i];
												T2_<?php echo $ret["phoneNumber"] ?>.addPoint(point, true, shift);	

												point[1] = my_data.humid[i];
												H0_<?php echo $ret["phoneNumber"] ?>.addPoint(point, true, shift);												
											}
										}	
									<?php endforeach ?>										
								} else {
									return -1;
								}							
					},
					cache: false,
					ifModified: true
				});
			}
			
			function plotData () {					
		
				<?php foreach ($returned as $key => $ret) : ?>
		
				chartForce_<?php echo $ret["phoneNumber"] ?> = new Highcharts.Chart(
				{
					chart: {
						renderTo: 'containerForce-<?php echo $ret["phoneNumber"] ?>',
						defaultSeriesType: 'line',
						zoomType: 'xy' 
						
						
					},
					title: {
						text: 'График усилия'
					},
					xAxis: {
						type: 'datetime',
						tickPixelInterval: 150,
						maxZoom: 20 * 1000					
					},
					yAxis: {
						
						title: {
							text: 'Усилие'							
						},										
						
						max: maxForce,
						min: -maxForce
						
						
					},	
					tooltip: {
						formatter: function() {
								return '<b>'+ this.series.name +'<\/b><br\/>'+
								Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br\/>'+
								'<b>Усилие: <\/b>'+							
								Highcharts.numberFormat(this.y, 2) +
								'<b> кгс<\/b><br\/>';
						}
					},
					legend: {
						layout: 'vertical',
						align: 'right',
						verticalAlign: 'top',
						x: -10,
						y: 100,
						borderWidth: 0
					},		
					plotOptions: {
						line: {
							marker: {
									enabled: false,
									states: {
										hover: {
											enabled: true,
											radius: 5
										}
									}
								}
						}
					},
					credits: {
						enabled: false
					},
					series: [{
						name: 'Фаза А',
						data: []
					}, {
						name: 'Фаза В',
						data: []
					}, {
						name: 'Фаза C',
						data: []
					}]			
				}
				);
				
				chartTemperature_<?php echo $ret["phoneNumber"] ?> = new Highcharts.Chart(
				{
					chart: {
						renderTo: 'containerTemperature-<?php echo $ret["phoneNumber"] ?>',
						defaultSeriesType: 'line',
						zoomType: 'xy' 
					},
					title: {
						text: 'График температуры'
					},
					xAxis: {
						type: 'datetime',
						tickPixelInterval: 150,
						maxZoom: 20 * 1000					
					},
					yAxis: {
						title: {
							text: 'Температура'							
						},					
						startOnTick: false,
						min: -50,
						max: 100,
						minPadding: 0.1,
						maxPadding: 1,
						
					},	
					tooltip: {
						formatter: function() {
								return '<b>'+ this.series.name +'<\/b><br\/>'+
								Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br\/>'+
								'<b>Температура: <\/b>'+							
								Highcharts.numberFormat(this.y, 2) +
								'<b> °C<\/b><br\/>';
						}
					},
					legend: {
						layout: 'vertical',
						align: 'right',
						verticalAlign: 'top',
						x: -10,
						y: 100,
						borderWidth: 0
					},	
					plotOptions: {
						line: {
							marker: {
									enabled: false,
									states: {
										hover: {
											enabled: true,
											radius: 5
										}
									}
								}							
						}
					},
					credits: {
						enabled: false
					},
					series: [{
						name: 'Фаза А',
						data: []
					}, {
						name: 'Фаза В',
						data: []
					}, {
						name: 'Фаза C',
						data: []
					}]
					
				}
				);							
				
			chartHumid_<?php echo $ret["phoneNumber"] ?> = new Highcharts.Chart(
				{
					chart: {
						renderTo: 'containerHumid-<?php echo $ret["phoneNumber"] ?>',
						defaultSeriesType: 'line',
						zoomType: 'xy' 
					},
					title: {
						text: 'График влажности'
					},
					xAxis: {
						type: 'datetime',
						tickPixelInterval: 150,
						maxZoom: 20 * 1000					
					},
					yAxis: {
						title: {
							text: 'Влажность'							
						},					
						startOnTick: false,
						min: 0,
						max: 100,
						minPadding: 0.1,
						maxPadding: 1,
						
					},	
					tooltip: {
						formatter: function() {
								return '<b>'+ this.series.name +'<\/b><br\/>'+
								Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br\/>'+
								'<b>Влажность: <\/b>'+							
								Highcharts.numberFormat(this.y, 2) +
								'<b> %<\/b><br\/>';
						}
					},
					legend: {
						layout: 'vertical',
						align: 'right',
						verticalAlign: 'top',
						x: -10,
						y: 100,
						borderWidth: 0
					},	
					plotOptions: {
						line: {
							marker: {
									enabled: false,
									states: {
										hover: {
											enabled: true,
											radius: 5
										}
									}
								}							
						}
					},
					credits: {
						enabled: false
					},
					series: [{
						name: 'Датчик',
						data: []
					}]
					
				}
			);
						
			<?php endforeach ?>	
		
			}
			
			$(document).ready(function() {
				<!-- Боян
				$("#accordion").accordion();
				$('.accordion .head').click(function() {
					$(this).next().toggle('slow');
					return false;
				}).next().hide();
				<!-- Табы 
				<?php foreach ($returned as $key => $ret) : ?>
					$("#tabs-<?php echo $ret["phoneNumber"] ?>").tabs();
				<?php endforeach ?>
			});

		
			$(function()
			{
			
				$('.date-pick').datePicker(
					{
						startDate: '01/01/1970',
						endDate: (new Date()).asString()
					}					
				);
				$('#start-date').bind(
					'dpClosed',
					function(e, selectedDates)
					{
						var d = selectedDates[0];
						if (d) {
							d = new Date(d);						
							date_begin = d;						
							$('#end-date').dpSetStartDate(d.addDays(1).asString());						
							
						}
					}
				);
				$('#end-date').bind(
					'dpClosed',
					function(e, selectedDates)
					{
						var d = selectedDates[0];
						if (d) {
							d = new Date(d);
							date_begin = date_begin.addDays(-1);
							date_end = d.addDays(1);
							$('#start-date').dpSetEndDate(d.addDays(-1).asString());																		
							getData(date_begin, date_end);
							plotData();
						}
					}
				);
			});
			