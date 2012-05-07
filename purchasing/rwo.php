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
$pagehigh = 24;
$pagecount = 0;
$totalpages = 0;
$totalrec = 0;
//Define Variables
$limit = 0;
switch($_POST['submit'])
{
	case "Prev":
		$limit = $_POST['limit'] - 1;
		break;
	case "Next":
		$limit = $_POST['limit'] + 1;
		break;
	case "Approve":
		if($_POST['approve']=='PUR')
		{
			$wo = $_POST['no'];
			mysql_query("UPDATE wo SET purch = CURDATE() WHERE no = '$wo'");
		}
		else
		{
			?>
			<script>alert("Please enter your departments code into the approve box.");</script>
			<?php
		}
		break;
}
//SQL variables for Main form
$csql = "SELECT * FROM contact WHERE id = '1'";
$cresult = mysql_query($csql);
$crow = mysql_fetch_array($cresult);
$id = mysql_real_escape_string($_GET['id']);
$sql = "SELECT *, DATE_FORMAT(date, '%m/%d/%Y') AS date, rev AS worev FROM customer INNER JOIN wo ON customer.id = wo.custid WHERE wo.purch IS NULL ORDER BY wo.no LIMIT $limit,1";
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
//Change limit if no more records
if(!$row)
{
	if($_POST['submit']=='Prev')
		$limit = $limit + 1;
	else
		$limit = $limit - 1;
}
?>
<h1>Purchasing Work Order Release Form</h1>
<form name="directions" action="rwo.php" method="POST">
<input type="hidden" name="limit" value="<?php echo $limit;?>"/>
<input type="hidden" name="no" value="<?php echo $no;?>"/>
<input type="submit" name="submit" value="Prev"/>
<input type="submit" name="submit" value="Next"/>
<input type="text" name="approve"/>
<input type="submit" name="submit" value="Approve"/>
</form>
<table>
<tr><td height="20"></td></tr>
</table>
<table width="100%" class="no">
<tr>
<th width="75%" style="font-size:20px;">Work Order</th>
<th class="no">WO No.</th>
<td class="no"><?php echo str_pad($no, 4, "0", STR_PAD_LEFT); if($worev){echo " - " . $worev;}?></td>
</tr>
<tr>
<th width="75%"></th>
<th class="no">Date:</th>
<td class="no"><?php echo $date;?></td>
</tr>
<tr>
<td colspan="3" height="10"></td>
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
$lsql = "SELECT * FROM woline WHERE no = '$no' ORDER BY line";
$lresult = mysql_query($lsql);
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
				</tr>
			
			<?php
			}
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
$nsql = "SELECT *, DATE_FORMAT(ndate, '%m/%d/%Y') AS ndate FROM wonote WHERE no = '$no'";
$nresults = mysql_query($nsql);
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
?>
<tr>
<td height="18" colspan="2" class="des"><?php echo $note;?></td>
<td class="line"><?php echo $auth . " " . $ndate;?></td>
</tr>
<?php
}
?>
</table>
<?php
//close mysql connection
mysql_close($con);
?>
</body>
</html>
