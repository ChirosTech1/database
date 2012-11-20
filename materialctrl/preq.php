<html>
<head><link rel="stylesheet" type="text/css" href="../style/req.css" /></head>
<?php
//Print or Preview
$printyes = $_GET['print'];
if($printyes){?>
<body onload="window.print()">
<?php
$printsel = count($_GET);
}
else{
?>
<body>
<?php
$printsel = 2;
}
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Maximum rows allowed on one page
$pagehigh = 13;
$pagecount = 0;
$totalpages = 0;
$totalrec = 0;
//SQL variables for Main form
$csql = "SELECT * FROM contact WHERE id = '1'";
$cresult = mysql_query($csql);
$crow = mysql_fetch_array($cresult);
$id = mysql_real_escape_string($_GET['id']);
$sql = "SELECT *, DATE_FORMAT(date, '%m/%d/%Y') AS date FROM matreq WHERE id = '$id'";
$result = mysql_query($sql);
$num = mysql_num_fields($result);
$field = array();
for($i = 0; $i < $num; ++$i)
{
	$fields = mysql_fetch_field($result, $i);
	$field[$i] = $fields->name;
}
$row = mysql_fetch_array($result);
//Define Variables based on SQL Field names
for($i = 0; $i < $num; ++$i)
{
	$$field[$i] = $row[$i];
}
//Define Page counter
$k = 0;
//Loop through all the pages
for ($i = 1;$i < $printsel; $i++)
{
//Only Loop through if a page was requested
if($_GET[$i])
{
?>
<table class="top">
<tr>
<th class="top">RESALE MATERIAL REQUISITION</th>
</tr>
<tr>
<th height="90"></th>
</tr>
</table>
<table class="mid">
<tr class="mid">
<th class="mid">Customer:</th>
<td class="mid"><?php echo $cust;?></td>
<th class="mid">Work Order:</th>
<td class="mid"><?php echo $wo;?></td>
</tr>
<tr>
<td height="50"><td>
</tr>
<tr class="mid">
<th class="mid">Date:</th>
<td class="mid"><?php echo $date;?></td>
<th class="mid">Requisition:</th>
<td class="mid"><?php echo $no;?></td>
</tr>
<tr>
<td height="50"><td>
</tr>
<tr class="mid">
<th class="mid">HCPN:</th>
<td class="mid"><?php echo $hpn;?></td>
<th class="mid">Description:</th>
<td class="mid"><?php echo $manpn . " " . $des;?></td>
</tr>
<tr>
<td height="50"><td>
</tr>
<tr class="mid">
<th class="mid">Quantity:</th>
<td class="mid"><?php echo $qty . " " . $unit;?></td>
<th class="mid">Used On:</th>
<td class="mid"><?php echo $used;?></td>
</tr>
<tr><td height="50"></td></tr>
</table>
<table class="notes">
<tr><th class="notes">Remarks:</th></tr>
<tr>
<td class="notes"><textarea class="notes" cols="100" rows="10"><?php echo $note;?></textarea></td>
</tr>
</table>
<?php

/************************************************************************
*									*
*		QC Information						*
*									*
************************************************************************/
$qsql = "SELECT * FROM material WHERE no = '$hpn'";
$qresult = mysql_query($qsql);
$qnum = mysql_num_fields($qresult);
$qfield = array();
for($i = 0; $i < $qnum; ++$i)
{
	$qfields = mysql_fetch_field($qresult, $i);
	$qfield[$i] = $qfields->name;
}
$qrow = mysql_fetch_array($qresult);
//Define Variables based on SQL Field names
for($i = 0; $i < $qnum; ++$i)
{
	if($$qfield[$i]  != $no)
	$$qfield[$i] = $qrow[$i];
}
?>
<table class="qc">
<tr>
<th colspan="4">QUALITY REVIEW</th>
</tr>
</table>
<table class="qc">
<tr>
<th class="qc" width="10%">BY:</th>
<td class="qc" width="45%">_</td>
<th class="qc" width="15%">DATE:</th>
<td class="qc" width="30%">_</td>
</tr>
</table>
<table class="qc">
<tr>
<th class="qc" width="15%">Cert To:</th>
<td class="qc" colspan="3" width="85%"><?php echo $spec;?></td>
</tr>
</table>
<table class="qc">
<tr>
<th class="qc" colspan="2" width="40%">Attachment 2 Clause</th>
<td class="qc" colspan="2" width="60%"><?php echo $qal;?></td>
</tr>
</table>
<table class="qc">
<tr>
<td width="25%"></td>
<th class="qc" colspan="2" width="15%">HP0165</th>
<td class="qc" colspan="2" width="35%"><?php echo $rec . ", " . $insp;?></td>
<td width="25%"></td>
</tr>
</table>
<table class="qc">
<tr>
<td width="25%"></td>
<th class="qc" colspan="2" width="25%">Revision</th>
<td class="qc" colspan="2" width="25%"> </td>
<td width="25%"></td>
</tr>
</table>
<p>QC 0228 09/11</p>
<div class="page-break"></div>
<?php
}
if($k < $pagehigh)
$k = 0;
else
$i = $i - 1;
}
if($l)
{
if($printyes)
{
?>
<SCRIPT LANGUAGE="javascript">
alert ("There are to many Lines. Please use 2 sheets per copy to print PO.");
</SCRIPT>
<?php
}
}
//close mysql connection
mysql_close($con);
?>
</body>
</html>
