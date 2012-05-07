<html>
<head><link rel="stylesheet" type="text/css" href="../style/material.css" /></head>
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
$sql = "SELECT *, DATE_FORMAT(date, '%m/%d/%Y') AS date, DATE_FORMAT(inhousedate, '%m/%d/%Y') AS inhousedate FROM po WHERE id = '$id'";
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
<td class="field"><?php echo $inhousedate;?></td>
<td class="field"><?php echo $job;?></td>
</tr>
</table>



<table class="lines">
<?php

/****************************************************************
*								*
*			GET PO LINE ITEMS			*
*								*
****************************************************************/
if($lastline){
$lsql = "SELECT * FROM poline WHERE no = '$no' AND line > '$lastline' ORDER BY line";
$lastline = 0;
$k = 0;
}
else{
if($k < $pagehigh)
$lsql = "SELECT * FROM poline WHERE no = '$no' ORDER BY line";
}
$lresult = mysql_query($lsql);
//Get Field Names for VAriables
$lnum = mysql_num_fields($lresult);
//Count values for Total Pages
$totalrec = mysql_num_rows($lresult) + 6;
$field = array();
for($j = 0; $j < $lnum; ++$j)
{

	$lfields = mysql_fetch_field($lresult, $j);
	$lfield[$j] = $lfields->name;
}
//Title headers for line items
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
//Chack page counter
//Loop through all line items
while($lrow = mysql_fetch_array($lresult))
{
if ($k < $pagehigh)
{
//Define Variables based on SQL Field names
for($j = 0; $j < $lnum; ++$j)
{
	$$lfield[$j] = $lrow[$j];
}
//Update Page Counter
$k = $k + 1;
//If there are more than 13 line items then alert the user
if ($k >= $pagehigh) 
$l = 1;
if ($k < $pagehigh)
{
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
else
{
if($lastline){}
else
$lastline = $line - 1;
}
}
}

/************************************************************************
*									*
*				GET PO NOTES				*
*									*
************************************************************************/

//Get Notes from MySQL
if($lastline){}
else
{
if($lastnote){
$nsql = "SELECT * FROM ponote WHERE no = '$no' AND callno = '$callno' AND id > '$lastnote'";
//Reset page counter to move to next requested form
$lastnote = 0;
$k = 0;}
else{
$nsql = "SELECT * FROM ponote WHERE no = '$no' AND callno = '$callno'";
}
}
$nresults = mysql_query($nsql);
$note = array();
for ($n = 0;$nrow = mysql_fetch_array($nresults); $n++)
{
//fill in notes
$note[$n] = $nrow['note'];
}

/************************************************************************
*									*
*		ORDER TOTALS						*
*									*
************************************************************************/
//If all lines have been printed then assign a footer value
if(!$lastline){
//Assign variable to foot to add extra blank lines
$foot = $pagehigh - 3 - $k;
$k = 0;}
//Check to see if calculations are correct
if ($foot < 0)
$foot = 0;
//Add 3 to $k for the "total" lines
$k = $k + 3;
if($k < $pagehigh) 
{
	//Add extra lines to fill page
	for ($foot = $foot; $foot; $foot--)
	{
	?>
	<tr>
	<td class="line"></td>
	<td class="line"></td>
	<td class="line"></td>
	<td height="18" class="des"></td>
	<td class="line"></td>
	<td class="line"></td>
	</tr>
	<?php
	}

?>
<tr>
<td class="line"></td>
<td class="line"></td>
<td class="line"></td>
<td class="des"><?php echo $note['0'];?></td>
<th class="line">Subtotal:</th>
<td class="line"><?php switch($_GET[$i]){case "Original Purchase Order": case "Numeric Copy": case "Accounting Copy": echo "$".$subtot; break;}?></td>
</tr>
<tr>
<td class="line"></td>
<td class="line"></td>
<td class="line"></td>
<td class="des"><?php echo $note['1'];?></td>
<th class="line">Tax:</th>
<td class="line"><?php switch($_GET[$i]){case "Original Purchase Order": case "Numeric Copy": case "Accounting Copy": echo "$".$taxtot; break;}?></td>
</tr>
<tr>
<td class="line"></td>
<td class="line"></td>
<td class="line"></td>
<td class="des"><?php echo $note['2'];?></td>
<th class="line">Total:</th>
<td class="line"><?php switch($_GET[$i]){case "Original Purchase Order": case "Numeric Copy": case "Accounting Copy": echo "$".$tot; break;}?></td>
</tr>
<?php
}
?>
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
<?php
//Page numbers
if(!$totalpages)
$totalpages = ceil($totalrec / $pagehigh);
if($k >= $pagehigh)
{
$pagecount = $pagecount + 1;
echo "Page ".$pagecount." of ".$totalpages;
}
else
{
	if($pagecount){
	$pagecount = $pagecount + 1;
	echo "Page ".$pagecount." of ".$totalpages;
	$pagecount = 0;
	}
}
?>

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
