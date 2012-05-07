<html>
<head><link rel="stylesheet" type="text/css" href="../style/shortpo.css" /></head>
<body>
<?php
//Database login
require_once('/home/hamby/auth/dbinfo.php');
$csql = "SELECT * FROM contact WHERE id = '1'";
$cresult = mysql_query($csql);
$crow = mysql_fetch_array($cresult);
$no = $_GET['no'];
$sql = "SELECT *, DATE_FORMAT(date, '%m/%d/%Y') AS date, DATE_FORMAT(indate, '%m/%d/%Y') AS indate FROM po WHERE no = '$no'";
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
for ($i = 1;$i < count($_GET); $i++)
{
if($_GET[$i])
{
?>
<table class="top">
<tr>
<th class="head" width="25%">PURCHASE ORDER</th>
<th class="head">HAMBY CORPORATION</th>
<th class="head" width="13%">No.</th>
<td class="headinfo" width="12%"><?php echo $row['no'];?></td>
</tr>
<tr>
<th class="head" width="25%">SHORT FORM</th>
<td class="headaddress"><?php echo $crow['address'] . "<br />" . $crow['city'] . ", " . $crow['state'] . " " . $crow['zip'] . "<br />Phone:" . $crow['phone'] . "<br />Fax:;" . $crow['fax'];?></td>
<th class="head"><?php if($rev){echo "REV: ";}?></th>
<td class="headinfo"><?php if($rev){echo $rev;}?></th>
</tr>
</table>
<table class="mid">
<tr>
<th class="header">TO:</th>
<th class="header">CONFIRMING WITH:</th>
<th class="header">DATE:</th>
</tr>
<tr>
<td class="field"><?php echo $supplier;?></td>
<td class="field"><?php echo $conf;?></td>
<td class="field"><?php echo $date;?></td>
</tr>
</table>
<table class="mid">
<tr>
<th class="header">DELIVER TO:</th>
<th class="header">SHIP VIA:</th>
<th class="header">IN HOUSE DATE:</th>
<th class="header">CHARGE TO JOB:</th>
</tr>
<tr>
<td class="field"><b>HAMBY CORP</b></td>
<td class="field"><?php echo $shp;?></td>
<td class="field"><?php echo $indate;?></td>
<td class="field"><?php echo $job;?></td>
</tr>
</table>
<table class="lines">
<?php
$lsql = "SELECT * FROM poline WHERE no = '$no'";
$lresult = mysql_query($lsql);
$lnum = mysql_num_fields($lresult);
$field = array();
for($j = 0; $j < $lnum; ++$j)
{
	$lfields = mysql_fetch_field($lresult, $j);
	$lfield[$j] = $lfields->name;
}
?>
<tr>
<th class="line">Line</th>
<th class="line">Qty</th>
<th class="line">Units</th>
<th class="line">Description</th>
<th class="line">Unit Price</th>
<th class="line">Line Price</th>
</tr>
<?php
$k = 0;
while($lrow = mysql_fetch_array($lresult))
{
//Define Variables based on SQL Field names
for($j = 0; $j < $lnum; ++$j)
{
	$$lfield[$j] = $lrow[$j];
}
//If there are more than 13 line items then alert the user
$k = $k + 1;
if ($k == 14) 
$l = 1;
?>
<tr>
<td class="line" width="5%"><?php echo $line;?></td>
<td class="line" width="5%"><?php echo $qty;?></td>
<td class="line" width="5%"><?php echo $unit;?></td>
<td class="des" ><?php echo $pn ." ".$des;?></td>
<td class="line" width="10%"><?php switch($_GET[$i]){case "Original Purchase Order": case "Numeric Copy": case "Accounting Copy": echo "$".$up; break;}?></td>
<td class="line" width="10%"><?php switch($_GET[$i]){case "Original Purchase Order": case "Numeric Copy": case "Accounting Copy": echo "$".$lp; break;}?></td>
</tr>
<?php
}
?>
<tr>
<td class="line"></td>
<td class="line"></td>
<td class="line"></td>
<td class="line"></td>
<th class="line">Subtotal:</th>
<td class="line"><?php switch($_GET[$i]){case "Original Purchase Order": case "Numeric Copy": case "Accounting Copy": echo "$".$subtot; break;}?></td>
</tr>
<tr>
<td class="line"></td>
<td class="line"></td>
<td class="line"></td>
<td class="line"></td>
<th class="line">Tax:</th>
<td class="line"><?php switch($_GET[$i]){case "Original Purchase Order": case "Numeric Copy": case "Accounting Copy": echo "$".$taxtot; break;}?></td>
</tr>
<tr>
<td class="line"></td>
<td class="line"></td>
<td class="line"></td>
<td class="line"></td>
<th class="line">Total:</th>
<td class="line"><?php switch($_GET[$i]){case "Original Purchase Order": case "Numeric Copy": case "Accounting Copy": echo "$".$tot; break;}?></td>
</tr>
</table>
<table class="bot">
<tr>
<th class="header" size="20%" rowspan="2"><?php echo $_GET[$i];?></th>
<th class="header">ORDERED BY:</th>
<th class="header">CHECKED BY:</th>
<th class="header" width="25%">SIGNED:</th>
</tr>
<tr>
<td class="field"><?php echo $ordered;?></td>
<td class="field"><?php echo $agent;?></td>
<th></th>
</tr>
</table>
<pre class="forminfo">ADM009 01-11						White/Blue - Purchasing, Green - Receiving, Pink - Accounting, Gold - Requisitioner</pre>
</form>
<div class="page-break"></div>
<?php
}
}
if($l)
{
?>
<SCRIPT LANGUAGE="javascript">
alert ("There are more than 13 Line items. Please use a full sheet to print PO.");
</SCRIPT>
<?php
}
//close mysql connection
mysql_close($con);
?>
</body>
</html>
