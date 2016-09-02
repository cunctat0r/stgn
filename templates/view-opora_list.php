<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<title>Установленные посты</title>

		<!-- 1. Add these JavaScript inclusions in the head of your page -->
		<script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="/js/jquery-ui-1.8.4.custom.min.js"></script>	
										
		<link rel="stylesheet" type="text/css" media="screen" href="/css/demo.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="/css/main.css" />
		
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
								<td>Номер телефона</td>																
								<td>Сервер 1</td>
							</tr>							
							<?php foreach ($returned as $key => $ret) : ?>
								<tr align="center">
									<td><?php echo  $ret["numLine"] ?></td>
									<td><?php echo  $ret["numOpora"] ?></td>									
									<td><?php echo $ret["phoneNumber"] ?></td>
									<td><?php echo $ret["receiver1"] ?></td>									
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
