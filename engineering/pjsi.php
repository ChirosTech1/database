<html>
<head><link rel="stylesheet" type="text/css" href="../style/wo.css" /></head>
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
$pagehigh = 20;
$pagecount = 0;
$totalpages = 0;
$totalrec = 0;
//SQL variables for Main form
$csql = "SELECT * FROM contact WHERE id = '1'";
$cresult = mysql_query($csql);
$crow = mysql_fetch_array($cresult);
$id = mysql_real_escape_string($_GET['id']);
$sql = "SELECT *, DATE_FORMAT(date, '%m/%d/%Y') AS date, rev AS worev FROM customer INNER JOIN wo ON customer.id = wo.custid WHERE wo.id = '$id'";
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
if($_GET[$i] && $_GET[$i] != 'Production Work Order')
{
?>
<table>
<tr><td height="80"></td></tr>
</table>
<table width="100%" class="no">
<tr>
<th width="75%" style="font-size:20px;"><?php echo $_GET[$i];?></th>
<th class="no">WO No.</th>
<td class="no"><?php echo str_pad($no, 4, "0", STR_PAD_LEFT); if($worev){echo " - " . $worev;}?></td>
</tr>
<tr>
<th width="75%"></th>
<th class="no">Date:</th>
<td class="no"><?php echo $date;?></td>
</tr>
<tr>
<td colspan="3" height="20"></td>
</tr>
</table>
<p/>
<table class="mid">
<tr>
<th style="text-align:right;height:30px;">Customer: </th>
<td> <?php echo $co;?></td>
</tr>
<tr>
<th style="text-align:right;height:30px;">Purchase Order: </th>
<td> <?php echo $po;?></td>
</tr>
<tr>
<th style="text-align:right;height:30px;">Ship Via: </th>
<td> <?php echo $shp;?></td>
</tr>
<tr>
<td height="40"></td>
</tr>
</table>
<table class="lines">
<?php

/****************************************************************
*								*
*			GET LINE ITEMS				*
*								*
****************************************************************/
if($lastline){
$lsql = "SELECT * FROM woline WHERE no = '$no' AND line > '$lastline' ORDER BY line";
$lastline = 0;
$k = 0;
}
else{
if($k < $pagehigh)
$lsql = "SELECT * FROM woline WHERE no = '$no' ORDER BY line";
}
$lresult = mysql_query($lsql);
//Total pages
$totalrec = mysql_num_rows($lresult);
//Get Field Names for VAriables
$lnum = mysql_num_fields($lresult);
$field = array();
for($j = 0; $j < $lnum; ++$j)
{

	$lfields = mysql_fetch_field($lresult, $j);
	$lfield[$j] = $lfields->name;
}
//Title headers for line items
?>
<tr>
<th class="headline" width="8%">Item</th>
<th class="headline" style="text-align:justify;">Description</th>
<th style="border-bottom:1px solid black;" class="headline" width="14%">Quantity</th>
<th class="headline" width="12%">Date Due</th>
<th class="headline" width="12%">Unit Cost</th>
<th class="headline" width="12%">Type</th>
</tr>
<tr><td height="15"></td></tr>
<?php
//Loop through all line items
while($lrow = mysql_fetch_array($lresult))
{
	//Define Variables based on SQL Field names
	for($j = 0; $j < $lnum; ++$j)
	{
		$$lfield[$j] = $lrow[$j];
	}
	$dsql = "SELECT *, DATE_FORMAT(ddue, '%m/%d/%Y') AS ddue FROM wodate WHERE no = '$no' AND line = '$line'";
	$dresult = mysql_query($dsql);
	$dnum = mysql_num_rows($dresult);
	if ($k + $dnum < $pagehigh)
	{
		//Update Page Counter
		$k = $k + $dnum;
		//If the	re are more than Max line items then alert the user
		if ($k == $pagehigh - 1) 
		$l = 1;
		for($d = 0;$drow = mysql_fetch_array($dresult); $d++)
		{
			if(!$d)
			{
			//See if P/N Has any EOs
			$eo = mysql_fetch_array(mysql_query("SELECT eo FROM pn WHERE no = '$pn'"));
			$eo = $eo['eo'];
			?>
				<tr>
				<td class="line" width="5%"><?php echo $line;?></td>
				<td class="des" ><?php if($rev){if($eo){echo $pn ." '".$rev . "+'";}else{echo $pn ." '".$rev . "'";}}else{echo $pn;}?></td>
				<td class="line" width="5%"><?php echo $drow['dqty'];?></td>
				<td class="line" width="5%"><?php echo $drow['ddue'];?></td>
				<td class="line" width="10%"><?php if($_GET[$i] != "Kit Work Order Copy" && $_GET[$i] != "QC Work Order Copy"){echo "$".$up;}?></td>
				<td class="line" width="10%"><?php  echo $type;?></td>
				</tr>
			<?php
			}
			else
			{?>
				<tr>
				<td class="line" width="5%"></td>
				<td class="des" ></td>
				<td class="line" width="5%"><?php echo $drow['dqty'];?></td>
				<td class="line" width="5%"><?php echo $drow['ddue'];?></td>
				<td class="line" width="10%"></td>
				<td class="line" width="10%"></td>
				</tr>
			
			<?php
			}
		}
	}
	else
	{
		if(!$lastline)
		$lastline = $line - .01;
	}
}
?>
<tr><td height="15"></td></tr>
</table>
<?php

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
$nsql = "SELECT *, DATE_FORMAT(ddue, '%m/%d/%Y') AS ddue FROM wonote WHERE no = '$no' AND id > '$lastnote'";
//Reset page counter to move to next requested form
$lastnote = 0;
$k = 0;}
else{
$nsql = "SELECT *, DATE_FORMAT(ndate, '%m/%d/%Y') AS ndate FROM wonote WHERE no = '$no'";
}
}
$nresults = mysql_query($nsql);
//Page Counter
$totalrec = $totalrec + mysql_num_rows($nresults) + 3;
?>
<table class="notes">
<tr>
<td height="20"></td>
</tr>
<tr>
<th style="text-align:justify;"><u>Work Order Notes and Comments:</u></th>
</tr>
<tr>
<td height="18" class="des"><?php if($quote){echo "Quote Number: " . $quote;}?></td>
<td class="line"><?php if($src){echo "Source: " . $src;}?></td>
<td class="line"><?php if($bfe){echo "BFE: " . $bfe;}?></td>
</tr>
<?php
while($nrow = mysql_fetch_array($nresults))
{
//fill in notes
$note = $nrow['note'];
$auth = $nrow['auth'];
$ndate = $nrow['ndate'];
//Add one to page counter
$k = $k + 1;
if ($k == $pagehigh - 1) 
$l = 1;
//Check if counter has reached page limit
if($k < $pagehigh)
{
?>
<tr>
<td height="18" colspan="2" class="des"><?php echo $note;?></td>
<td class="line"><?php echo $auth . " " . $ndate;?></td>
</tr>
<?php
}
//Variable to see last note
else
{
//Only assign variable if this is the first loop where page counter is too high
if($lastline){}
else
{
if ($lastnote){}
else{
$lastnote = $nrow['id'] - 1;
}
}
}
}
/************************************************************************
*									*
*		ORDER TOTALS						*
*									*
************************************************************************/
if(!$lastline)
{
	if(!$lastnote){
	$foot = $pagehigh - 1 - $k;
	$k = 0;}
}
if($foot < 0)
$foot = 0;
$k = $k + 1;
if ($k == $pagehigh - 1) 
$l = 1;
if($k < $pagehigh)
{
	for ($foot = $foot; $foot; $foot--)
	{
	?>
	<tr>
	<td height="18" class="des"></td>
	<td class="line"></td>
	<td class="line"></td>
	</tr>
	<?php
	}
?>
<tr>
<td class="line"></td>
<th class="line"></th>
<td class="line"><?php if($_GET[$i] != "Kit Work Order Copy" && $_GET[$i] != "QC Work Order Copy"){echo "$".$tot;}?></td>
</tr>
<?php
}
?>
</table>
</form>
</div>
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

/************************************************************************
*									*
*			Production Work Order Form			*
*									*
************************************************************************/
if($_GET[$i] == 'Production Work Order')
{
?>
<table class="header">
<tr><td height="20"></td></tr>
<tr>
<td></td>
<th><?php echo $_GET[$i];?></th>
<th>WO No: </th>
<td><?php echo str_pad($no, 4, "0", STR_PAD_LEFT); if($worev){echo " - " . $worev;}?></td>
</tr>
</table>
<table class="head">
<tr>
<th style="text-align:right;">Customer: <th><td><?php echo $co;?></td>
<th style="text-align:right;">Purchase Order: </th><td><?php echo $po;?></td>
<th style="text-align:right;">Date: </th><td><?php echo $date;?></td>
</tr>
<tr><td height="20"></td></tr>
</table>
<table width="100%" class="address">
<tr>
<td width="10%" rowspan="4" style="text-align:center;">Bill <br/> To:</td>
<td><b><?php echo $bco;?></b></td>
<td width="10%" rowspan="4" style="text-align:center;">Ship <br/> To:</td>
<td><b><?php echo $sco;?></b></td>
</tr>
<tr>
<td><?php echo $bcont;?></td>
<td><?php echo $scont;?></td>
</tr>
<tr>
<td><?php echo $baddress;?></td>
<td><?php echo $saddress;?></td>
</tr>
<tr>
<td><?php echo $bcity.", ".$bstate." ".$bzip;?></td>
<td><?php echo $scity.", ".$sstate." ".$szip;?></td>
</tr>
<tr>
<td colspan="4" height="60"></td>
</tr>
</table>
<table>
<th>Ship Via: </th>
<td><?php echo $shp;?></td>
</table>
<table class="lines">
<?php

/****************************************************************
*								*
*			GET LINE ITEMS				*
*								*
****************************************************************/
if($lastline){
$lsql = "SELECT * FROM woline WHERE no = '$no' AND line > '$lastline' ORDER BY line";
$lastline = 0;
$k = 0;
}
else{
if($k < $pagehigh)
$lsql = "SELECT * FROM woline WHERE no = '$no' ORDER BY line";
}
$lresult = mysql_query($lsql);
//Total pages
$totalrec = mysql_num_rows($lresult);
//Get Field Names for VAriables
$lnum = mysql_num_fields($lresult);
$field = array();
for($j = 0; $j < $lnum; ++$j)
{

	$lfields = mysql_fetch_field($lresult, $j);
	$lfield[$j] = $lfields->name;
}
//Title headers for line items
?>
<tr>
<th class="headline" width="8%">Item</th>
<th class="headline" style="text-align:justify;">Description</th>
<th style="border-bottom:1px solid black;" class="headline" width="14%">Quantity</th>
<th class="headline" width="12%">Date Due</th>
<th class="headline" width="12%">Unit Cost</th>
<th class="headline" width="12%">Type</th>
</tr>
<tr><td height="15"></td></tr>
<?php
//Loop through all line items
while($lrow = mysql_fetch_array($lresult))
{
	//Define Variables based on SQL Field names
	for($j = 0; $j < $lnum; ++$j)
	{
		$$lfield[$j] = $lrow[$j];
	}
	$dsql = "SELECT *, DATE_FORMAT(ddue, '%m/%d/%Y') AS ddue FROM wodate WHERE no = '$no' AND line = '$line'";
	$dresult = mysql_query($dsql);
	$dnum = mysql_num_rows($dresult);
	if ($k + $dnum < $pagehigh)
	{
		//Update Page Counter
		$k = $k + $dnum;
		//If the	re are more than Max line items then alert the user
		if ($k == $pagehigh - 1) 
		$l = 1;
		for($d = 0;$drow = mysql_fetch_array($dresult); $d++)
		{
			if(!$d)
			{
			?>
				<tr>
				<td class="line" width="5%"><?php echo $line;?></td>
				<td class="des" ><?php if($rev){echo $pn ." '".$rev . "'";}else{echo $pn;}?></td>
				<td class="line" width="5%"><?php echo $drow['dqty'];?></td>
				<td class="line" width="5%"><?php echo $drow['ddue'];?></td>
				<td class="line" width="10%"><?php if($_GET[$i] != "Route Work Order Copy"){echo "$".$up;}?></td>
				<td class="line" width="10%"><?php  echo $type;?></td>
				</tr>
			<?php
			}
			else
			{?>
				<tr>
				<td class="line" width="5%"></td>
				<td class="des" ></td>
				<td class="line" width="5%"><?php echo $drow['dqty'];?></td>
				<td class="line" width="5%"><?php echo $drow['ddue'];?></td>
				<td class="line" width="10%"></td>
				<td class="line" width="10%"></td>
				</tr>
			
			<?php
			}
		}
	}
	else
	{
		if(!$lastline)
		$lastline = $line - .01;
	}
}
?>
<tr><td height="15"></td></tr>
</table>
<p/>
<?php
/************************************************************************
*									*
*		Production WO Invoices					*
*									*
************************************************************************/
?>
<table class="invoices">
<tr>
<th class="invinfo">Invoice</th>
<td class="invinfo"></td>
<td class="invinfo"></td>
<td class="invinfo"></td>
<td class="invinfo"></td>
<td class="invinfo"></td>
<td class="invinfo"></td>
<td class="invinfo"></td>
<td class="invinfo"></td>
<td class="invinfo"></td>
<td class="invinfo"></td>
</tr>
<tr>
<th class="invinfo">Date</th>
<td class="invinfo"></td>
<td class="invinfo"></td>
<td class="invinfo"></td>
<td class="invinfo"></td>
<td class="invinfo"></td>
<td class="invinfo"></td>
<td class="invinfo"></td>
<td class="invinfo"></td>
<td class="invinfo"></td>
<td class="invinfo"></td>
</tr>
<?php
$lresult = mysql_query($lsql);
while($lrow = mysql_fetch_array($lresult))
{
?>
<tr>
<th class="invinfo"><?php echo $lrow['line'];?></th>
<td class="invinfo"> / </td>
<td class="invinfo"> / </td>
<td class="invinfo"> / </td>
<td class="invinfo"> / </td>
<td class="invinfo"> / </td>
<td class="invinfo"> / </td>
<td class="invinfo"> / </td>
<td class="invinfo"> / </td>
<td class="invinfo"> / </td>
<td class="invinfo"> / </td>
</tr>
<?php
}
?>
</table>


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
