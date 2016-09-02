<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Архив</title>
		
		<link rel="stylesheet" type="text/css" media="screen" href="../css/datePicker.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="../css/demo.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="../css/main.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="../css/black-tie/jquery-ui-1.8.4.custom.css" />
		
		<script type="text/javascript" src="../js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="../js/jquery-ui-1.8.4.custom.min.js"></script>		
		
		<script type="text/javascript" src="../js/highcharts.src.js"></script>

		
		<script type="text/javascript" src="../js/date.js"></script>
		<script type="text/javascript" src="../js/date_ru_utf8.js"></script>

		
		<script type="text/javascript" src="../js/jquery.datePicker.js"></script> 
		
		<SCRIPT type="text/javascript">
		
			
				var chartForce; 
				var chartTemperature; 
				var chartHumid; 
				
			var addGMT = 10;			
			
			var maxForce= 1500;
			var minForce;
			
			var date_begin;
			var date_end;
			var oporaSelected=0;
			
			
			function getData(date_to_get_begin, date_to_get_end, oporaSelected) {						
				if (oporaSelected == 0) {
					alert("Сначала выберите опору");
					return -1;
				}
				$.ajax({
					url: 'return-json.php', //запрос всех данных за определенную дату
					dataType: 'json',
					data: ({date_begin:date_to_get_begin.toString(), date_end:date_to_get_end.toString(), phone_selected:oporaSelected.toString()}),
					success: function (hash_table) {
								//return -1;
								
								var my_data = eval(hash_table);	
								
								if (my_data != undefined) {
									
									
										var F0 = chartForce.series[0];
										var F1 = chartForce.series[1];
										var F2 = chartForce.series[2];														
									
										var T0 = chartTemperature.series[0];
										var T1 = chartTemperature.series[1];
										var T2 = chartTemperature.series[2];
									
										var H0 = chartHumid.series[0];
									
										var shift = chartForce.series[0].data.length > 2000; 
									
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

												point[1] = my_data.humid[i];
												H0.addPoint(point, true, shift);																								
										}										
								} else {
									return -1;
								}							
					},
					cache: false,
					ifModified: true
				});
			};
			
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
							getData(date_begin, date_end, oporaSelected);							
							//$("#oporaSelect :first").attr("selected", "selected");
							oporaSelected = 0;							
							plotData();
						}
					}
				);
			});
			
			function plotData () {													
			
				chartForce = new Highcharts.Chart(
					{
						chart: {
							renderTo: 'containerForce',
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
						
						//enabled: false
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
												enabled: false,
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
				//chartForce.showLoading();	
				
				chartTemperature = new Highcharts.Chart(
					{
						chart: {
							renderTo: 'containerTemperature',
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
							maxPadding: 1
							
						},
						
						tooltip: {
							/*
							formatter: function() {
									return '<b>'+ this.series.name +'<\/b><br\/>'+
									Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br\/>'+
									'<b>Температура: <\/b>'+							
									Highcharts.numberFormat(this.y, 2) +
									'<b> °C<\/b><br\/>';
							}
							*/
							enabled: false
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
												enabled: false,
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
				//chartTemperature.showLoading();		
				
				chartHumid = new Highcharts.Chart(
					{
						chart: {
							renderTo: 'containerHumid',
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
							maxPadding: 1
							
						},	
						
						tooltip: {
							/*
							formatter: function() {
									return '<b>'+ this.series.name +'<\/b><br\/>'+
									Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) +'<br\/>'+
									'<b>Влажность: <\/b>'+							
									Highcharts.numberFormat(this.y, 2) +
									'<b> %<\/b><br\/>';
							}
							*/
							enabled: false
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
												enabled: false,
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
			};
			//chartHumid.showLoading();
			
			
			$(document).ready(function() {
				
				/*
				$("#accordion").accordion();
				$('.accordion .head').click(function() {
					$(this).next().toggle('slow');
					return false;
				}).next().hide();
				*/
				$("#tabs").tabs();
				$('#oporaSelect').change(function() {					
					oporaSelected = $('#oporaSelect').val()
					$('#start-date').attr("value", "");
					$('#end-date').attr("value", "");																		
				});	
			});
			

		</SCRIPT>
				
		
		
		
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
					<li><a href="http://glazed-frost.sahen.elektra.ru/map.xml">Текущие данные</a></li>
					<li><a href="./table.php">Сводная таблица</a></li>
					<li><a href="./opora_list.php">Установленные посты</a></li>					
					
				</ul>
			</div>
		</div>
		<div id="content-container">			
			<div id="content-container2">
				<div id="content-container3">
					<div id="content">
						<!-- <div id="accordion">							-->
							
								<div id="charts0" >									
									<div id="tabs" style="width: 750px; height: 500px; margin: 0 auto">
										<ul>
											<li><a href="#fragment-1"><span>Усилие</span></a></li>
											<li><a href="#fragment-2"><span>Температура</span></a></li>
											<li><a href="#fragment-3"><span>Влажность</span></a></li>
										</ul>
										<div id="fragment-1" style="width: 600px; height: 400px; margin: 0 auto">
												<div id="containerForce" style="width: 550px; height: 400px; margin: 0 auto"></div>																							
										</div>
										<div id="fragment-2" style="width: 600px; height: 400px; margin: 0 auto">											
												<div id="containerTemperature" style="width: 550px; height: 400px; margin: 0 auto"></div>																		
										</div>
										<div id="fragment-3" style="width: 600px; height: 400px; margin: 0 auto">											
												<div id="containerHumid" style="width: 550px; height: 400px; margin: 0 auto"></div>																		
										</div>											
									</div>						
								</div>														
						<!-- </div> -->
					</div>
					<div id="aside">						
						<div class="opora" id="opora" style="margin: left">					
							<fieldset>									
									<legend>Опора</legend>									
										<p>											
											<br>
											<select id="oporaSelect">									
											<option selected value="0" selected>Выберите опору</option>
											<?php foreach ($returned as $key => $ret) : ?>
												<option value="<?php echo $ret["phoneNumber"] ?>">
													<?php echo "Опора " . $ret["numOpora"] . " - " . "линия " . $ret["numLine"] ?>
												</option>
											<?php endforeach ?>
											</select>
										</p>										
							</fieldset>
							
						</div>
						<div class="date" style="margin: left">					
							
							<form name="chooseDateForm" id="chooseDateForm" action="#">
								
								<fieldset>
									
									<legend>Выберите дату</legend>									
										<p>
											<label for="start-date">Начало выборки:</label>
											<input name="start-date" id="start-date" class="date-pick" />										
										</p>
										<br>
										<p>
											<label for="end-date">Конец выборки:</label>
											<input name="end-date" id="end-date" class="date-pick" />
										</p>                        									
								</fieldset>
							</form>
						</div>
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
