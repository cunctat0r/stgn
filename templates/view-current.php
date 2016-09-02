<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Текущие данные</title>

		<!-- 1. Add these JavaScript inclusions in the head of your page -->
		<script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="/js/jquery-ui-1.8.4.custom.min.js"></script>	
		
		<script type="text/javascript" src="/js/highcharts.src.js"></script>
		
		<script type="text/javascript">
		<?php foreach ($returned as $key => $ret) : ?>
			var chartForce_<?php echo $ret["phoneNumber"] ?>; // global
			var chartTemperature_<?php echo $ret["phoneNumber"] ?>; // global
			var chartHumid_<?php echo $ret["phoneNumber"] ?>; // global
		<?php endforeach ?>			
		var addGMT = 10;
		
		var maxForce= 1500;
		var minForce;
		
		var date_begin;
		var date_end;
		
		
		/**
		 * Request data from the server, add it to the graph and set a timeout to request again
		 */
		 
		function requestData() {
			$.ajax({
				url: 'return-live-data.php', //запрос всех данных за сегодня 
				dataType: 'json',
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
								
								var shift = chartForce_<?php echo $ret["phoneNumber"] ?>.series[0].data.length > 200; // shift if the series is longer than 20										
							
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
							};							
							setTimeout(requestData, 30000);									
				},
				cache: false,
				ifModified: true
			});
		}
		
		
		function getData() {						
			$.ajax({
				url: 'return-today-data.php', //запрос всех данных за сегодня 
				dataType: 'json',
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
								
								var shift = chartForce_<?php echo $ret["phoneNumber"] ?>.series[0].data.length > 200; // shift if the series is longer than 20										
							
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
					zoomType: 'xy' ,
					events: {
						<?php if ($key == 0) : ?>
						load: requestData
						<?php endif ?>
					}
					
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
					min: 0
					
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
			// Боян
			$("#accordion").accordion();
			$('.accordion .head').click(function() {
				$(this).next().toggle('slow');
				return false;
			}).next().hide();
			// Табы 
			<?php foreach ($returned as $key => $ret) : ?>
				$("#tabs-<?php echo $ret["phoneNumber"] ?>").tabs();
			<?php endforeach ?>
			// Получаем данные
			getData();
			plotData();
						
		});
			
		</script>
		
		<link rel="stylesheet" type="text/css" media="screen" href="/css/datePicker.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="/css/demo.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="/css/main.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="/css/black-tie/jquery-ui-1.8.4.custom.css" />
	</head>

	<body>
	<div id="full-field">
		<div id="head-container">
			<div id="header">
				<h1>
					Мониторинг гололедно-ветровых нагрузок
				</h1>
			</div>
		</div>
		<div id="navigation-container">
			<div id="navigation">
				<ul>
					<li><a href="./index.html">Home</a></li>
					<li><a href="./archive.php">Архив</a></li>
					<li><a href="./current.php">Текущие данные</a></li>
					<li><a href="./table.php">Сводная таблица</a></li>

					
				</ul>
			</div>
		</div>
		<div id="content-container">
			<div id="content-container2"> 
				<div id="content-container3">
					<div id="content" >
						<div id="accordion" >
							<?php foreach ($returned as $key => $ret) : ?>
								<h3><a href="#"><?php echo "Опора " . $ret["numOpora"] . " - " . "линия " . $ret["numLine"] ?></a></h3>
							
								<div id="charts0-<?php echo $ret["phoneNumber"] ?>" >
									<div id="tabs-<?php echo $ret["phoneNumber"] ?>" style="width: 650px; height: 500px; margin: 0 auto">
										<ul>
											<li><a href="#fragment-1-<?php echo $ret["phoneNumber"] ?>"><span>Усилие</span></a></li>
											<li><a href="#fragment-2-<?php echo $ret["phoneNumber"] ?>"><span>Температура</span></a></li>
											<li><a href="#fragment-3-<?php echo $ret["phoneNumber"] ?>"><span>Влажность</span></a></li>
										</ul>
										<div id="fragment-1-<?php echo $ret["phoneNumber"] ?>" style="width: 600px; height: 400px; margin: 0 auto">
											<!-- <div id="charts-<?php echo $ret["phoneNumber"] ?>" style="width: 550px; height: 400px; margin: 0 auto"> -->
												<div id="containerForce-<?php echo $ret["phoneNumber"] ?>" style="width: 550px; height: 400px; margin: 0 auto"></div>												
											<!-- </div> -->
										</div>
										<div id="fragment-2-<?php echo $ret["phoneNumber"] ?>" style="width: 600px; height: 400px; margin: 0 auto">
											<!-- <div id="charts-<?php echo $ret["phoneNumber"] ?>" style="width: 550px; height: 400px; margin: 0 auto"> -->
												<div id="containerTemperature-<?php echo $ret["phoneNumber"] ?>" style="width: 550px; height: 400px; margin: 0 auto"></div>							
											<!-- </div> -->
										</div>
										<div id="fragment-3-<?php echo $ret["phoneNumber"] ?>" style="width: 600px; height: 400px; margin: 0 auto">
											<!-- <div id="charts-<?php echo $ret["phoneNumber"] ?>" style="width: 550px; height: 400px; margin: 0 auto"> -->
												<div id="containerHumid-<?php echo $ret["phoneNumber"] ?>" style="width: 550px; height: 400px; margin: 0 auto"></div>							
											<!-- </div> -->
										</div>											
									</div>						
								</div>							
							<?php endforeach ?>	
						</div>
						
					</div>
					<div id="aside">
						
					</div>
				</div>
			</div>
			<div id="footer-container">
				<div id="footer">
					Copyright © ООО НТЦ Инструмент-микро, 2010
				</div>
			</div>
		</div>	
	</div>
	</body>

</html>