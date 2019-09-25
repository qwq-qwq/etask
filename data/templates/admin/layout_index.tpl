<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title>e-tasks.bukva.ua</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link type="text/css" href="css/public.css" rel="stylesheet">
	<link type="text/css" href="css/datepicker.css" rel="stylesheet">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.4.1.js"></script>
	<script type="text/javascript" src="js/jquery.ui.core.js"></script>
	<script type="text/javascript" src="js/ui.datepicker.js"></script>
	<script type="text/javascript" src="js/functions.js"></script>
	<script type="text/javascript" src="js/task.js"></script>
	<script type="text/javascript" src="js/jquery.mask.min.js"></script>
</head>
<body>
<div align="center">
<div style="width: 980px;" align="left">
	<div style="width: 980px;">
		<div class="poslovica" style="width: 100%;">
		<div style="padding-top: 10px; float: left; width: 420px;"><img src="/img/logo.jpg" /></div>
		<div style="padding-top: 20px;"></div>
		</div>
		<div style="clear: both;"></div>
	</div>
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="5">&nbsp;</td>
		<td valign="top" width="185">
			{include file="blocks/menuleft.tpl"}
		</td>
		<td id="contenttd" valign="top" width="790">
			<div class="bodytop"><div class="bodytoptext">{$pagetitle}</div></div>
			<div style="min-height: 200px;" class="content" id="contentdivobj">
				<div id="innerDivObj">{include file="blocks/filters.tpl"}</div>
				<div class="cleaner"></div>&nbsp;
			</div>
			<div class="bodybot"></div>
		</td>
	</tr>
	<tr>
		<td width="5">&nbsp;</td>
		<td id="contenttd" valign="top" width="975" colspan="2">

			<div class="bodylisttop">
				<div class="bodytoptext">
					<a name="results"></a>Просмотр задач
				</div>
				<div class="exportbutton" style="float: right">
					<input type="button" value="Экспорт в Excel" onclick="exporttasks()" />
				</div>
				{if $has_courier_access}
				<div class="exportbutton" style="float:left;margin-left:10px;">
					<input type="button" value="Курьерская доставка по Киеву" onclick="exportCSreport()" />
				</div>
				<div class="exportbutton" style="float:left;margin-left:10px;">
					<input type="button" value="Автолюкс и Укрпочта из Киева" onclick="exportAUPreport()" />
				</div>
				<div class="exportbutton" style="float:left;margin-left:10px;">
					<input type="button" value="Перемещения по Киеву" onclick="exportTransfersReport()" />
				</div>
				<div class="exportbutton" style="float:left;margin-left:10px;">
					<input type="button" value="Новая Почта" onclick="exportNewPostReport()" />
				</div>
				{/if}
			</div>
			<div style="min-height: 200px;" class="content" id="contentdivobj">
				<div id="innerDivObj">
				{if count($messages)}
				<center>
					<div>
						{foreach from=$messages item=msgi}
							{if $msgi.error}
								<div style="font-size: 12px; color: red;">
									{$msgi.msg}<br/>
								</div>
							{/if}
							{if !$msgi.error}
								<div style="font-size: 12px; color: green;">
									{$msgi.msg}<br/>
								</div>
							{/if}
						{/foreach}
					</div>
				</center>
				{/if}
				{include file="$page"}
				</div>
				<div class="cleaner"></div>
			</div>
			<div class="bodylistbot"></div>
		</td>
	</tr>
	</table>
</div>
</div>
<div style="padding-top: 5px; font-size: 10px; font-family: verdana; color: rgb(120, 120, 120);" align="center">Developed by <a href="http://www.miritec.com/" style="color: rgb(120, 120, 120); font-weight: bold; text-decoration: none;">miritec</a><br><br></div>
<div style="padding-top: 5px; font-size: 10px; font-family: verdana; color: rgb(120, 120, 120);" align="center">and by <a href="http://www.photobysergey.com/" style="color: rgb(120, 120, 120); font-weight: bold; text-decoration: none;">sergey</a><br><br></div>
<script type="text/javascript">
	{literal}
	$(document).ready(function (){
		$('#Contact_phone').mask('+38 (000) 0000000');
		$("#Contact_phone").on( "click", function() {
			$("#Contact_phone").val("+38");
		});
	});
	{/literal}
</script>
</body>
</html>