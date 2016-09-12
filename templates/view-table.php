<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Сводная таблица</title>

		<!-- 1. Add these JavaScript inclusions in the head of your page -->
		<script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="/js/jquery-ui-1.8.4.custom.min.js"></script>	
				
		<script type="text/javascript">
		
		function timestamp2date(timestamp) {
			var theDate = new Date(timestamp);
			//return theDate.toUTCString();
			return theDate.toLocaleString();
		}
		
		<?php foreach ($returned as $key => $ret) : ?>
			var F0_<?php echo $ret["phoneNumber"] ?>; // global
			var F1_<?php echo $ret["phoneNumber"] ?>; // global
			var F2_<?php echo $ret["phoneNumber"] ?>; // global
			var F3_<?php echo $ret["phoneNumber"] ?>; // global
			var F4_<?php echo $ret["phoneNumber"] ?>; // global
			var F5_<?php echo $ret["phoneNumber"] ?>; // global
			var F6_<?php echo $ret["phoneNumber"] ?>; // global
			var F7_<?php echo $ret["phoneNumber"] ?>; // global
			var F8_<?php echo $ret["phoneNumber"] ?>; // global
			var F9_<?php echo $ret["phoneNumber"] ?>; // global
			var net_region_<?php echo $ret["pnoneNumber"] ?>;

		<?php endforeach ?>			
		var addGMT = 0;
		
		var maxForce= 1500;
		var minForce;
		
		var maxA;
		var maxB;
		var maxC;
		
		var date_begin;
		var date_end;				
		
		function get_today_data () {
			$.ajax({
				url: 'return-today-data.php', //запрос всех данных за сегодня 
				dataType: 'json',
				success: function (hash_table) {
							var my_data = eval(hash_table);	
							
							if (my_data != undefined) {
							
							<?php foreach ($returned as $key => $ret) : ?>
							
								var F0_<?php echo $ret["phoneNumber"] ?> = 0;
								var F1_<?php echo $ret["phoneNumber"] ?> = 0;
								var F2_<?php echo $ret["phoneNumber"] ?> = 0;									
								var F3_<?php echo $ret["phoneNumber"] ?> = 0;									
								var F4_<?php echo $ret["phoneNumber"] ?> = 0;									
								var F5_<?php echo $ret["phoneNumber"] ?> = 0;									
								var F6_<?php echo $ret["phoneNumber"] ?> = 0;								
								var F7_<?php echo $ret["phoneNumber"] ?> = 0;								
								var F8_<?php echo $ret["phoneNumber"] ?> = 0;	
								var F9_<?php echo $ret["phoneNumber"] ?> = 0;														
							
								var T0_<?php echo $ret["phoneNumber"] ?> = 0;
								var T1_<?php echo $ret["phoneNumber"] ?> = 0;
								var T2_<?php echo $ret["phoneNumber"] ?> = 0;
								
								var H0_<?php echo $ret["phoneNumber"] ?> = 0;
								
								var net_region_<?php echo $ret["pnoneNumber"] ?> = "qwew";
								var last_time = 0;
								
								for (var i = 0; i < my_data.date.length; i++)
								{
									if (my_data.phoneNumber[i] === "<?php echo $ret["phoneNumber"] ?>") {					
										
										last_time = timestamp2date(my_data.date[i] + addGMT * 60 * 60 * 1000);

								<?php		for ($sensor = 0; $sensor < 4; $sensor++) { ?>
											$('#F<?php echo $sensor?>_<?php echo $ret["phoneNumber"] ?>').css("font-weight", "bold");
											$('#F<?php echo $sensor?>_<?php echo $ret["phoneNumber"] ?>').text(my_data.F<?php echo $sensor?>[i]);										
											if (my_data.F<?php echo $sensor?>[i] > 900) {
												$('#F<?php echo $sensor?>_<?php echo $ret["phoneNumber"] ?>').css("background-color", "red");
											} else if (my_data.F<?php echo $sensor?>[i] <= 0) {
												$('#F<?php echo $sensor?>_<?php echo $ret["phoneNumber"] ?>').css("background-color", "red");
											} else {
												$('#F<?php echo $sensor?>_<?php echo $ret["phoneNumber"] ?>').css("background-color", "#0f0");											
											}
										
										<?php } ?>
										$('#time_<?php echo $ret["phoneNumber"] ?>').text(last_time);
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
 		
		
		
		function get_new_data () {
			$.ajax({
				url: 'return-live-data.php', //запрос новых данных
				dataType: 'json',
				success: function (hash_table) {
							var my_data = eval(hash_table);	
							
							if (my_data != undefined) {
							
							<?php foreach ($returned as $key => $ret) : ?>
							
								var F0_<?php echo $ret["phoneNumber"] ?> = 0;
								var F1_<?php echo $ret["phoneNumber"] ?> = 0;
								var F2_<?php echo $ret["phoneNumber"] ?> = 0;														
								var F3_<?php echo $ret["phoneNumber"] ?> = 0;
							
								var T0_<?php echo $ret["phoneNumber"] ?> = 0;
								var T1_<?php echo $ret["phoneNumber"] ?> = 0;
								var T2_<?php echo $ret["phoneNumber"] ?> = 0;
								
								var H0_<?php echo $ret["phoneNumber"] ?> = 0;

								
								var net_region_<?php echo $ret["pnoneNumber"] ?> = "qwew";
								var last_time = 0;
								
								for (var i = 0; i < my_data.date.length; i++)
								{
									if (my_data.phoneNumber[i] === "<?php echo $ret["phoneNumber"] ?>") {									
										last_time = timestamp2date(my_data.date[i] + addGMT * 60 * 60 * 1000);
								<?php		for ($sensor = 0; $sensor < 4; $sensor++) { ?>
								$('#F<?php echo $sensor ?>_<?php echo $ret["phoneNumber"] ?>').css("font-weight", "bold");
								$('#F<?php echo $sensor ?>_<?php echo $ret["phoneNumber"] ?>').text(my_data.F<?php echo $sensor ?>[i]);										
								if (my_data.F<?php echo $sensor ?>[i] > 900) {
									$('#F<?php echo $sensor ?>_<?php echo $ret["phoneNumber"] ?>').css("background-color", "red");
								} else if (my_data.F<?php echo $sensor ?>[i] <= 0) {
									$('#F<?php echo $sensor ?>_<?php echo $ret["phoneNumber"] ?>').css("background-color", "red");
								} else {
									$('#F<?php echo $sensor ?>_<?php echo $ret["phoneNumber"] ?>').css("background-color", "#0f0");											
								}
										<?php } ?>
										$('#time_<?php echo $ret["phoneNumber"] ?>').text(last_time);
										$('#net_region_<?php echo $ret["netRegion"]?>').text(my_data.netRegion)
									}
								}
							<?php endforeach ?>
							} else {
								return -1;
							}							
							setTimeout(get_new_data, 15000);
				},
				cache: false,
				ifModified: true
			});
		}
		
		$(document).ready(function() {				
			get_today_data();
			get_new_data();
                        
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
					
					<li><a href="../leaflet/index.php">Карта</a></li>
					<li><a href="./table.php">Сводная таблица</a></li>
					<li><a href="./opora_list.php">Установленные посты</a></li>
					
				</ul>
			</div>
		</div>
		<div id="content-container">
			<div id="content-container2"> 
				<div id="content-container3">
					<div id="content" >
						<div id="common_table">
							<table border=1>							
							<tr align="center">
								<td>Номер линии</td>
								<td>Номер опоры</td>
								<td>Фаза А, кг</td>								
								<td>Фаза В, кг</td>
								<td>Фаза С, кг</td>
								<td>Трос, кг</td>
								<td>Время выхода в эфир</td>
								<td>Сетевой район</td>
							</tr>							
							<?php foreach ($returned as $key => $ret) : ?>
								<tr align="center">
									<td><?php echo  $ret["numLine"] ?></td>
									<td><?php echo  $ret["numOpora"] ?></td>			
									<td id="F0_<?php echo $ret["phoneNumber"] ?>"></td>		
									<td id="F1_<?php echo $ret["phoneNumber"] ?>"></td>		
									<td id="F2_<?php echo $ret["phoneNumber"] ?>"></td>	
									<td id="F3_<?php echo $ret["phoneNumber"] ?>"></td>
									<td id="time_<?php echo $ret["phoneNumber"] ?>"></td>
									<td><?php echo $ret["netRegion"] ?></td>					
								</tr>
							<?php endforeach ?>							
							</table>
						</div>											
					</div>
					<div id="aside">
						
					</div>
				</div>
			</div>
			<div id="footer-container">
				<div id="footer">
					Copyright © ООО НТЦ Инструмент-микро, 2012
				</div>
			</div>
		</div>	
	</div>
	</body>

</html>
