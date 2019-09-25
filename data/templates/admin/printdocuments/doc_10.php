<!--Этикетка-->

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 3.2//EN">
<HTML>
<HEAD>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<TITLE>Этикетка</TITLE>
</HEAD>
<BODY>
<table align="center" width="600" border="1" bordercolor="black" cellpadding="0" cellspacing="0">
    <tr>
        <td bgcolor="#CCCCCC" colspan="2" width="600">
            <p align="center"><font face="Verdana" size="5"><b>Внутрішнє переміщення</b></font></p>
        </td>
    </tr>
    <tr>
        <td bgcolor="#CCCCCC" colspan="2" width="600">
            <p>&nbsp;</p>
        </td>
    </tr>
    <tr>
        <td colspan="2" width="600">
            
            <table border="0" align="center" cellpadding="0" cellspacing="0" width="600">
             
                <tr>
                    <td rowspan="2" width="250">
                        <p align="center"><font face="Verdana" size="7"><b>Bukva</b></font></p>
                    </td>
                    <td rowspan="2" valign="top" width="175">
                        <p align="right"><font face="Verdana" size="1"><b>Дата та час пакування:</b></font></p>
                    </td>
                    <td valign="top" width="175">
                        <p align="left"><font face="Verdana" size="1"><b>&nbsp;<?=date('d.m.Y H:i:s')?></b></font></p>
                    </td>
                </tr>
			  <tr>
			 <td width="250" align="left"><img src="<?=PROJECT_URL?>document-39-<?=$transf['intNumberInvoice']?>.png" /></td>
			 </tr>				
            </table>
        </td>
    </tr>
    <tr>
        <td bgcolor="#CCCCCC" colspan="2" width="600">
            <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
			<tr>
                    <td width="200">
                        <p align="right"><font face="Verdana" size="3"><b>Відправник:&nbsp;&nbsp;&nbsp;</b></font></p>
                    </td>
                    <td width="400">
                        <p align="left"><font face="Verdana" size="3"><b><?=$transf['from_shop_name']?></b></font></p>
                    </td>
			</tr>
			<tr>
                    <td width="200">
                        <p align="right"><font face="Verdana" size="3">Склад:&nbsp;&nbsp;&nbsp;</font></p>
                    </td>
                    <td width="400">
                        <p align="left"><font face="Verdana" size="3"><?=$transf['from_wh_name']?></font></p>
                    </td>
			</tr>
			</table>
        </td>
    </tr>
    <tr>
        <td bgcolor="#CCCCCC" colspan="2" width="600">
            <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
			<tr>
                    <td width="200">
                        <p align="right"><font face="Verdana" size="3"><b>Отримувач:&nbsp;&nbsp;&nbsp;</b></font></p>
                    </td>
                    <td width="400">
                        <p align="left"><font face="Verdana" size="3"><b><?=$transf['to_shop_name']?></b></font></p>
                    </td>
			</tr>
			<tr>
                    <td width="200">
                        <p align="right"><font face="Verdana" size="3">Склад:&nbsp;&nbsp;&nbsp;</font></p>
                    </td>
                    <td width="400">
                        <p align="left"><font face="Verdana" size="3"><?=$transf['to_wh_name']?></font></p>
                    </td>
			</tr>
			</table>
        </td>
    </tr>
    <tr>
        <td colspan="2" width="600">
            <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
			<tr>
                    <td width="200">
                        <p align="right"><font face="Verdana" size="3"><b>Відправник:&nbsp;&nbsp;&nbsp;</b></font></p>
                    </td>
                    <td width="400">
                        <p align="left"><font face="Verdana" size="3"><b><?=$transf['from_shop_name']?></b></font></p>
                    </td>
			</tr>
			<tr>
                    <td width="200">
                        <p align="right"><font face="Verdana" size="3"><b>Адреса:&nbsp;&nbsp;&nbsp;</b></font></p>
                    </td>
                    <td width="400">
                        <p align="left"><font face="Verdana" size="3"><?=$transf['from_shop_adress']?></font></p>
                    </td>
			</tr>
			</table>
        </td>
    </tr>
    <tr>
        <td colspan="2" width="600">
            <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
			<tr>
                    <td width="200">
                        <p align="right"><font face="Verdana" size="3"><b>Отримувач:&nbsp;&nbsp;&nbsp;</b></font></p>
                    </td>
                    <td width="400">
                        <p align="left"><font face="Verdana" size="3"><b><?=$transf['to_shop_name']?></b></font></p>
                    </td>
			</tr>
			<tr>
                    <td width="200">
                        <p align="right"><font face="Verdana" size="3"><b>Адреса:&nbsp;&nbsp;&nbsp;</b></font></p>
                    </td>
                    <td width="400">
                        <p align="left"><font face="Verdana" size="3"><?=$transf['to_shop_adress']?></font></p>
                    </td>
			</tr>
			</table>
        </td>
    </tr>
    <tr>
        <td colspan="2" width="600">
            <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%">
			<tr>
                    <td width="200">
                        <p align="right"><font face="Verdana" size="3"><b>Накладна #:&nbsp;&nbsp;&nbsp;</b></font></p>
                    </td>
			<td>
			<table border="1" cellpadding="0" cellspacing="0" align="left" width="200">
			<tr>
                                <td bgcolor="#CCCCCC">
                                    <p align="center"><font face="Verdana" size="4"><b><?=$transf['intNumberInvoice']?></b></font></p>
                                </td>
			</tr></table>
			</td>
			</tr>
			<tr>
                    <td width="200">
                        <p align="right"><font face="Verdana" size="3"><b>Працівник:&nbsp;&nbsp;&nbsp;</b></font></p>
                    </td>
                    <td width="400">
                        <p align="left"><font face="Verdana" size="3"><b><u><?=$packer['varFIO']?></u></b></font></p>
                    </td>
			</tr>
			<tr>
                    <td width="200">
                        <p align="right"><font face="Verdana" size="3"><b>Коробка:&nbsp;&nbsp;&nbsp;</b></font></p>
                    </td>
                    <td width="400">
                        <p align="left"><font face="Verdana" size="3"><b><u>1</u> з <u>1</u></b></font></p>
                    </td>
			</tr>
			</table>
        </td>
   </tr> 

  
<tr>
        <td valign="top" width="150">
            <p align="right"><font face="Verdana" size="3"><b>Коментар:&nbsp;&nbsp;&nbsp;</b></font>
</p>
        </td>
<td bgcolor="#CCCCCC" valign="top"><font face="Verdana" size="3"><b><?=$transf['comment']?></b></font></td>
</tr>
</table>
</BODY>
</HTML>