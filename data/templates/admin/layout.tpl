<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<title>{$pagetitle}</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link type="text/css" href="css/public.css" rel="stylesheet">
	<link type="text/css" href="css/jquery.ui.css" rel="stylesheet">
	<link type="text/css" href="css/datepicker.css" rel="stylesheet" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script type="text/javascript" src="js/jquery.ui.tabs.js"></script>
	<script type="text/javascript" src="js/jquery.ui.datetimepicker.js"></script>
	<script type="text/javascript" src="js/ui.datepicker.js"></script>
	<script type="text/javascript" src="js/jquery.MultiFile.js"></script>
	<script type="text/javascript" src="js/functions.js"></script>
	<script type="text/javascript" src="js/order.js"></script>
</head>
<body>
<div align="center">
<div style="width: 980px;" align="left">
	<div style="width: 980px;">
		<div class="poslovica" style="width: 100%;">
			<div style="padding-top: 10px; float: left; width: 420px;"><a href="index.php"><img src="/img/logo.jpg" /></a></div>
			<div style="padding-top: 20px;"></div>
		</div>
		<div style="clear: both;"></div>
	</div>
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="5">&nbsp;</td>
		<td id="contenttd" valign="top" width="975" colspan="2">
			<div class="bodylisttop"><div class="bodytoptext">{$pagetitle}</div></div>
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
</body>
</html>