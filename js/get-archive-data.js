var chartForce; // global
		var chartTemperature; // global
		var addGMT = 4;
		
		var maxForce= 30;
		var minForce;
		
		var date_begin;
		var date_end;
		
		
		/**
		 * Request data from the server, add it to the graph and set a timeout to request again
		 */
		 /*
		function requestData() {
			$.ajax({
				url: 'live-server-data.php', 
				success: function(point) {
				
					var series = chart.series[0],
						shift = series.data.length > 20; // shift if the series is longer than 20					
					// add the point
					chart.series[0].addPoint(eval(point), true, shift);
					
					
					// call it again after 30 second
					setTimeout(requestData, 30000);	
				},
				cache: false,
				ifModified: true
			});
		}
		*/
		
		function getData(date_to_get_begin, date_to_get_end) {						
			$.ajax({
				url: 'return-json.php', //запрос всех данных за определенную дату
				dataType: 'json',
				data: ({date_begin:date_to_get_begin.toString(), date_end:date_to_get_end.toString()}),
				success: function (hash_table) {
							var my_data = eval(hash_table);	
							
							if (my_data != undefined) {
							
								var F0 = chartForce.series[0];
								var F1 = chartForce.series[1];
								var F2 = chartForce.series[2];														
							
								var T0 = chartTemperature.series[0];
								var T1 = chartTemperature.series[1];
								var T2 = chartTemperature.series[2];
								
								var shift = chartForce.series[0].data.length > 200; // shift if the series is longer than 20										
							
								var point = [];
								
								for (var i = 0; i < my_data.date.length; i++)
								{
									point[0] = my_data.date[i] + addGMT * 60 * 60 * 1000;
									point[1] = my_data.F0[i];
													
									F0.addPoint(point, true, shift);						
									
									point[1] = my_data.F1[i];
									F1.addPoint(point, true, shift);						
									
									point[1] = my_data.F2[i];
									F2.addPoint(point, true, shift);	
									
									point[1] = my_data.T0[i];
									T0.addPoint(point, true, shift);
									
									point[1] = my_data.T1[i];
									T1.addPoint(point, true, shift);
									
									point[1] = my_data.T2[i];
									T2.addPoint(point, true, shift);
								}								
							} else {
								return -1;
							}							
				},
				cache: false,
				ifModified: true
			});
		}
		
		function plotData () {					
		
			var optionsF = {
				chart: {
					renderTo: 'containerForce',
					defaultSeriesType: 'line',
					zoomType: 'x' /*,
					events: {
						load: getData
					}
					*/
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
					min: 0,
					
				},	
				tooltip: {
					formatter: function() {
			                return '<b>'+ this.series.name +'</b><br/>'+
							Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br/>'+
							'<b>Усилие: </b>'+							
							Highcharts.numberFormat(this.y, 2) +
							'<b> кгс</b><br/>';
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
				
			};
			
			var optionsT = {
				chart: {
					renderTo: 'containerTemperature',
					defaultSeriesType: 'line',
					zoomType: 'x' /*,
					events: {
						load: getData
					}
					*/
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
			                return '<b>'+ this.series.name +'</b><br/>'+
							Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br/>'+
							'<b>Температура: </b>'+							
							Highcharts.numberFormat(this.y, 2) +
							'<b> °C</b><br/>';
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
				
			};
			
			//getData();
			chartForce = new Highcharts.Chart(optionsF);
			chartTemperature = new Highcharts.Chart(optionsT);				
			
		}
		
		$(document).ready(function() {
			$("#tabs").tabs();
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