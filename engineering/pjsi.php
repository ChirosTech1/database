<html>
<head><link rel="stylesheet" type="text/css" href="../style/jsi.css" /></head>
<?php
//Print or Preview
$printyes = $_GET['print'];
if($printyes){?>
<body onload="window.print()">
<?php
}
else{
?>
<body>
<?php
}
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Maximum rows allowed on one page
$pagehigh = 20;
$pagecount = 0;
$totalpages = 0;
$totalrec = 0;
//Define Variables
$table = 'jsi';
$jtable = 'jsijob';
$mtable = 'jsimat';
$stable = 'jsispec';
//SQL variables for Main form
$id = mysql_real_escape_string($_GET['id']);
$sql = "SELECT * FROM $table WHERE id = '$id'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
//Define PN Variable to referenece in sql to get jobs,mats,specs,etc
$no = $row['no'];
//Define Variables based on SQL Field names
for($i = 0; $i < $num; ++$i)
{
	$$field[$i] = $row[$i];
}
//Define Page counter 
$pagecounter = 0;
?>
<!--
---------------------------------------------------------
.							.
.		HEADER SECTION				.
.							.
---------------------------------------------------------
--!>
<div class="page_header">
<table class="page_header">
	<tr>
		<td>PTH#______________</td>
		<th rowspan="2">JOB SUMMARY AND INSPECTION</th>
		<td>JSI#___________________</td>
	</tr><?php $pagecounter++;?>
	<tr>
		<td>Final Assembly: <?php echo $row['final'];?></td>
		<td>Page 1 of ---</td>
	</tr><?php $pagecounter++;?>
</table>
<table class="page_header">
	<tr>
		<td>Date Due:______________</td>
		<td>Qty Due:_____</td>
		<td>Qty:____</td>
		<td>SN:______________</td>
		<td>WO:______________</td>
		<td>Cust:_______________</td>
	</tr><?php $pagecounter++;?>
</table>
<table class="page_header">
	<tr>
		<td>Part Number: <?php echo $row['no'];?></td>
		<td>Remarks:  <?php echo $row['rmks'];?></td>
		<td>Run__Panels @  <?php echo $row['ppp'];?> Parts/Panel</td>
	</tr><?php $pagecounter++;?>
</div>
<!--
---------------------------------------------------------
.							.
.		Procedure Lines				.
.							.
---------------------------------------------------------
--!>

<div class="procedure">

<!-- PROCEDURE HEADER --!>

<table class="procedure">
	<tr>
		<th rowspan="2" class="procedure">##</th>
		<th rowspan="2" class="procedure">Job Description</th>
		<th rowspan="2" class="procedure">Materials and Specs</th>
		<th rowspan="2" class="procedure">QC</th>
		<th rowspan="2" class="procedure">Date</th>
		<th rowspan="2" class="procedure">S</th>
		<th colspan="2" class="procedure">Times</th>
		<th rowspan="2" class="procedure">Emp</th>
		<th colspan="2" class="procedure">Qty</th>
		<th rowspan="2" class="procedure">QC</th>
		<th rowspan="2" class="procedure">Qual Spec</th>
		<th rowspan="2" class="procedure">ST</th>
		<th rowspan="2" class="procedure">Rejs</th>
		<th rowspan="2" class="procedure">ST</th>
		<th rowspan="2" class="procedure">Date</th>
	</tr><?php $pagecounter++;?>
	<tr>
		<th class="procedure">In</th>
		<th class="procedure">Out</th>
		<th class="procedure">I</th>
		<th class="procedure">O</th>
	</tr><?php $pagecounter++;?>
<?php

/************************************************
*						*
*	Procedure Job Lines			*
*						*
************************************************/

//Get Job Descriptions based on Part Number
$jsql = "SELECT * FROM $jtable WHERE pn = '$no' ORDER BY no";
$jresult = mysql_query($jsql);
//Loop through all Jobs
for($j = 1;$jrow = mysql_fetch_array($jresult);$j++)
{
	//Check for skipped Job numbers
	if($jrow['no'] == $j)
	{
?>
	<tr>
		<td class="procedure"><?php echo $jrow['no'];?></td>
		<td class="procedure"><?php echo $jrow['job'];?></td>
		<td class="procedure"><?php echo $jrow['matspec'];?></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
	</tr><?php $pagecounter++;?>
<?php
	}
	//Add Blank Spaces for Skipped Job numbers
	else
	{
	//Keep adding blank numbers until a valid job is found
	while($jrow['no'] != $j)
	{
?>
	<tr>
		<td class="procedure"><?php echo $j;?></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
	</tr><?php $pagecounter++;?>
<?php	
		//Add +1 to $j to increment
		$j++;
	}
	//Add Valid Job Number after blanks are added
?>
	<tr>
		<td class="procedure"><?php echo $jrow['no'];?></td>
		<td class="procedure"><?php echo $jrow['job'];?></td>
		<td class="procedure"><?php echo $jrow['matspec'];?></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
	</tr><?php $pagecounter++;?>
<?php
	}
}
//Add 5 extra rows to allow space
for($e = 0;$e!=5;$e++)
{
?>
	<tr>
		<td class="procedure"><?php echo $j;?></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
		<td class="procedure"></td>
	</tr><?php $pagecounter++;?>
<?php
		//increment value of $j for blank Job Number values
		$j++;
}
?>
</table>
</div>

<!-----------------------------------------------
.						.
.		Material List			.
.						.
----------------------------------------------!>

<div class="material">
<table class="material">
	<tr>
		<th class="material" colspan="7">Material List</th>
		<th class="material" width="500px"></th>
	</tr><?php $pagecounter++;?>
	<tr>
		<th class="material">##</th>
		<th class="material">Qty</th>
		<th class="material">PN</th>
		<th class="material" colspan="2">Description</th>
		<th class="material">Specification</th>
		<th class="material">Certs</th>
		<th class="material"></th>
	</tr><?php $pagecounter++;?>

<?php
/************************************************
*						*
*	Material List Lines			*
*						*	
************************************************/
//Get Materials based on Part Number
$msql = "SELECT $mtable.*, material.pn AS matpn, material.des AS matdes, material.type AS mattype, material.spec AS matspec FROM $mtable INNER JOIN material ON $mtable.mat = material.no WHERE $mtable.pn = '$no' ORDER BY no";
$mresult = mysql_query($msql);
for($m = 1;$mrow = mysql_fetch_array($mresult);$m++)
{
	//Check for skipped Job numbers
	if($mrow['no'] == $m)
	{
?>
	<tr>
		<td class="material"><?php echo $mrow['no'];?></td>
		<td class="material"><?php echo $mrow['qty'] . " " . $mrow['unit'];?></td>
		<td class="material"><?php echo $mrow['mat'];?></td>
		<td class="material"><?php echo $mrow['matpn'] . " " . $mrow['mattype'];?></td>
		<td class="material"><?php echo $mrow['matdes'];?></td>
		<td class="material"><?php echo $mrow['matspec'];?></td>
		<td class="material"></td>
		<td class="material"></td>
	</tr><?php $pagecounter++;?>
<?php
	}
	//Add Blank Spaces for Skipped Job numbers
	else
	{
	//Keep adding blank numbers until a valid job is found
	while($mrow['no'] != $m)
	{
?>
	<tr>
		<td class="material"><?php echo $m;?></td>
		<td class="material"></td>
		<td class="material"></td>
		<td class="material"></td>
		<td class="material"></td>
		<td class="material"></td>
		<td class="material"></td>
		<td class="material"></td>
	</tr><?php $pagecounter++;?>
<?php	
	//Add +1 to $m to increment
	$m++;
	}
	//Add Valid Material Number after blanks are added
?>
	<tr>
		<td class="material"><?php echo $mrow['no'];?></td>
		<td class="material"><?php echo $mrow['qty'] . " " . $mrow['unit'];?></td>
		<td class="material"><?php echo $mrow['mat'];?></td>
		<td class="material"><?php echo $mrow['matpn'] . " " . $mrow['mattype'];?></td>
		<td class="material"><?php echo $mrow['matdes'];?></td>
		<td class="material"><?php echo $mrow['matspec'];?></td>
		<td class="material"></td>
		<td class="material"></td>
	</tr><?php $pagecounter++;?>

<?php

	}
}
//Add 4 extra rows to allow space
for($e = 0;$e!=4;$e++)
{
?>
	<tr>
		<td class="material"><?php echo $m;?></td>
		<td class="material"></td>
		<td class="material"></td>
		<td class="material"></td>
		<td class="material"></td>
		<td class="material"></td>
		<td class="material"></td>
		<td class="material"></td>
	</tr><?php $pagecounter++;?>

<?php
	//increment value of $j for blank Job Number values
	$m++;
}
?>
</table>
</div>

<!-----------------------------------------------
.						.
.	Blue Prints and Specifications		.
.						.
----------------------------------------------!>
<div class="specs">
<table class="specs">
<!-- HEADER SECTION --!>
	<tr>
		<th class="specs" colspan="9">Blue Prints and Specifications</th>
	</tr><?php $pagecounter++;?>
	<tr>
		<th class="specs"></th>
		<th class="specs">B/P OR Spec</th>
		<th class="specs">Description</th>
		<th class="specs"></th>
		<th class="specs">B/P OR Spec</th>
		<th class="specs">Description</th>
		<th class="specs"></th>
		<th class="specs">B/P OR Spec</th>
		<th class="specs">Description</th>
	</tr><?php $pagecounter++;?>
<?php

/************************************************
*						*
*	B/P and Specs List			*
*						*
************************************************/
$ssql = "SELECT *, LENGTH(let) AS len FROM $stable WHERE pn = '$no' ORDER BY len, let";
$sresult = mysql_query($ssql);
//Specifiy Letter variable to use as filler for blank spots
$let = "A";
//Loop through Specification Letters 3 at a time
for($s = 0; $srow = mysql_fetch_array($sresult); $s++)
{
	//Check the letter against the letter counter to check for blanks
	if($srow['let'] == $let)
	{
		//Fill in data 3 rows at a time
		if(fmod($s,3) != 0)
		{
?>
			<td class="specs"><?php echo $srow['let'];?></td>
			<td class="specs"><?php echo $srow['spec'];?></td>
			<td class="specs"><?php echo $srow['des'];?></td>
<?php
		}
		//End 3 column and start new column
		else
		{
			if($s != 0)
			{
?>
				</tr><?php $pagecounter++;?>
<?php
			}
?>
		<tr>	
			<td class="specs"><?php echo $srow['let'];?></td>
			<td class="specs"><?php echo $srow['spec'];?></td>
			<td class="specs"><?php echo $srow['des'];?></td>
<?php
		}
	}
	//Fill In Blank Letters until Valid Letter is found
	else
	{
		//Continue to fill in blank rows until valid letter is found
		while($srow['let'] != $let)
		{
			//Fill in data 3 rows at a time
			if(fmod($s,3) != 0)
			{
?>
				<td class="specs"><?php echo $let;?></td>
				<td class="specs"></td>
				<td class="specs"></td>
<?php
			}
			//End 3 column and start new column
			else
			{
				if($s != 0)
				{
?>
					</tr><?php $pagecounter++;?>
<?php
				}
?>
			<tr>	
				<td class="specs"><?php echo $let;?></td>
				<td class="specs"></td>
				<td class="specs"></td>
<?php
			}
			//Increment Letter variable
			$let++;
			$s++;
		}	
		//Fill in Valid letter found
		if(fmod($s,3) != 0)
		{
?>
			<td class="specs"><?php echo $srow['let'];?></td>
			<td class="specs"><?php echo $srow['spec'];?></td>
			<td class="specs"><?php echo $srow['des'];?></td>
<?php
		}
		//End 3 column and start new column
		else
		{
			if($s != 0)
			{
?>
				</tr><?php $pagecounter++;?>
<?php
			}
?>
		<tr>	
			<td class="specs"><?php echo $srow['let'];?></td>
			<td class="specs"><?php echo $srow['spec'];?></td>
			<td class="specs"><?php echo $srow['des'];?></td>
<?php
		}
	}
$let++;
}
?>
</table>
</div>

<!-----------------------------------------------
.						.
.		Flysheet			.
.						.
----------------------------------------------!>
<?php
//Check to see if a flysheet is even needed
$fsql = "SELECT * FROM matlist INNER JOIN ";
?>
<div class="flysheet">
<table class="flysheet">
	<tr>
		<th class="flysheet" colspan="15">FLYSHEET</th>
	</tr><?php $pagecounter++;?>
	<tr>
		<th class="flysheet">No</th>
		<th class="flysheet">Document #</th>
		<th class="flysheet">Rev</th>
		<th class="flysheet">Type</th>
		<th class="flysheet">Stat</th>
		<th class="flysheet">No</th>
		<th class="flysheet">Document #</th>
		<th class="flysheet">Rev</th>
		<th class="flysheet">Type</th>
		<th class="flysheet">Stat</th>
		<th class="flysheet">No</th>
		<th class="flysheet">Document #</th>
		<th class="flysheet">Rev</th>
		<th class="flysheet">Type</th>
		<th class="flysheet">Stat</th>
	</tr><?php $pagecounter++;?>
</table>
</div>

<?php echo $pagecounter;?>
<?php

















?>
