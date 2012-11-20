<html>
<head>
</head>
<body>
<?php
//Database login
require_once('/home/hamby/auth/dbinfo.php');
//Define sql table info
$table = 'jsijob';
$primaryfield = 'no';
$secondaryfield = 'pn';
$mtable = 'jsimat';
$mprimaryfield = 'no';
$stable = 'jsispec';
$sprimaryfield = 'let';
//Define other variables
$q = $_POST['q'];
$linkvalue = mysql_real_escape_string($_POST['linkvalue']);
$dline = mysql_real_escape_string($_POST['dline']);
$did = mysql_real_escape_string($_POST['did']);
//Get Field names from Table
$fieldsql = "SELECT * FROM $table LIMIT 1";
$fieldresult = mysql_query($fieldsql);
$num = mysql_num_fields($fieldresult);
$field = array();
for($i = 0; $i < $num; ++$i)
{
	$fields = mysql_fetch_field($fieldresult, $i);
	$field[$i] = $fields->name;
}
//Define Variables based on SQL Field names
for($i = 0; $i < $num; ++$i)
{
	$$field[$i] = mysql_real_escape_string($_POST[$field[$i]]);
}
//New Query Variable
for($i = 0; $i < $num; ++$i)
{
	$sqlescape = mysql_real_escape_string($_POST[$field[$i]]);
	if($sqlescape)
	{
		$newquery .= "$field[$i],";
		$newvalues .= "'$sqlescape',";
	}
}
$newquery .= "no,taxable";
$newvalues .= "'$linkvalue','0'";

//Get Date Field names from Table
$dfieldsql = "SELECT * FROM $dtable LIMIT 1";
$dfieldresult = mysql_query($dfieldsql);
$dnum = mysql_num_fields($dfieldresult);
$dfield = array();
for($i = 0; $i < $num; ++$i)
{
	$dfields = mysql_fetch_field($dfieldresult, $i);
	$dfield[$i] = $dfields->name;
}
//Define Date Variables based on SQL Field names
for($i = 0; $i < $dnum; ++$i)
{
	$$dfield[$i] = mysql_real_escape_string($_POST[$dfield[$i]]);
}

//Check which action to update and execute SQL
switch ($q)
{
	case "Next":
		//Go to next record in set
		$limit = $limit + 1;
		$sql = "SELECT * FROM $table WHERE no = '$linkvalue' ORDER BY $primaryfield";
		$dsql = "SELECT * $dateformat FROM $dtable WHERE no = '$linkvalue' ORDER BY $secondaryfield";
		break;
	case "Prev":
		//Go to prev record in set
		$limit = $limit - 1;
		$sql = "SELECT * FROM $table WHERE no = '$linkvalue' ORDER BY $primaryfield";
		$dsql = "SELECT * $dateformat FROM $dtable WHERE no = '$linkvalue' ORDER BY $secondaryfield";
		break;
	case "New":
		//Make insert query
		mysql_query("INSERT INTO $table ($primaryfield,pn,job) VALUES ('$no','$linkvalue','$job')");
		$sql = "SELECT * FROM $table WHERE pn = '$linkvalue' ORDER BY $primaryfield";
		$msql = "SELECT * FROM $mtable WHERE pn = '$linkvalue' ORDER BY $mprimaryfield";
		$ssql = "SELECT * FROM $stable WHERE pn = '$linkvalue' ORDER BY $sprimaryfield";
		break;
	case "update":
		if($q == 'update')
		{
		//Check to See if the Job Number changed
		$sql = "SELECT * FROM $table WHERE id = '$id'";
		$result = mysql_query($sql);
		$oldno = mysql_fetch_array($result);
		$oldno = $oldno['no'];
		//Check to see if job number changed already exists
		$sql = "SELECT * FROM $table WHERE no = '$no' AND pn = '$linkvalue'";
		$result = mysql_query($sql);
		$existno = mysql_fetch_array($result);
		$existno = $existno['id'];
		//Change all jobno for mat/spec if job changed
		if ($oldno != $no && !$existno)
		{
			mysql_query("UPDATE $mtable SET jobno = '$no' WHERE jobno = '$oldno' AND pn = '$linkvalue'");
			mysql_query("UPDATE $stable SET jobno = '$no' WHERE jobno = '$oldno' AND pn = '$linkvalue'");
		} 
		else if ($oldno != $no && $existno)
		{
			//Get id's of material to be changed
			$omsql = "SELECT * FROM $mtable WHERE pn = '$linkvalue' AND jobno = '$oldno'";
			$omresult = mysql_query($omsql);
			while ($omrow = mysql_fetch_array($omresult)){
				$omid .= "id = '" . $omrow['id'] . "' OR ";
			} 
			$omid = substr_replace($omid,"",-4);
			//Get id's of new material to be changed to old no
			$nmsql = "SELECT * FROM $mtable WHERE pn = '$linkvalue' AND jobno = '$no'";
			$nmresult = mysql_query($nmsql);
			while ($nmrow = mysql_fetch_array($nmresult)){
				$nmid .= "id = '" . $nmrow['id'] . "' OR ";
			} 
			$nmid = substr_replace($nmid,"",-4);
			//Get id's of specification to be changed
			$ossql = "SELECT * FROM $stable WHERE pn = '$linkvalue' AND jobno = '$oldno'";
			$osresult = mysql_query($ossql);
			while ($osrow = mysql_fetch_array($osresult)){
				$osid .= "id = '" . $osrow['id'] . "' OR ";
			} 
			$osid = substr_replace($osid,"",-4);
			//Get id's of new specification to be changed to old no
			$nssql = "SELECT * FROM $stable WHERE pn = '$linkvalue' AND jobno = '$no'";
			$nsresult = mysql_query($nssql);
			while ($nsrow = mysql_fetch_array($nsresult)){
				$nsid .= "id = '" . $nsrow['id'] . "' OR ";
			} 
			$nsid = substr_replace($nsid,"",-4);
			//Switch Jobno based on ids
			mysql_query("UPDATE $mtable SET jobno = '$no' WHERE $omid");
			mysql_query("UPDATE $mtable SET jobno = '$oldno' WHERE $nmid");
			mysql_query("UPDATE $stable SET jobno = '$no' WHERE $osid");
			mysql_query("UPDATE $stable SET jobno = '$oldno' WHERE $nsid");
			//Update job table
			mysql_query("UPDATE $table SET no = '$oldno' WHERE id = '$existno'");
		}
		//Update Query Variable
		for($i = 0; $i < $num; ++$i)
		{
			$f = $field[$i];
			$sqlescape = mysql_real_escape_string($_POST[$f]);
			if($sqlescape)
			{
				if($f != 'id')
				$updatequery .= "$f='$sqlescape', ";
			}
		}
		$updatequery .= "id = '$id'";
		//Update all fields using updatequery variable
		mysql_query("UPDATE  $table SET $updatequery WHERE id = '$id'");
		}
	case "Delete":
		if($q == 'Delete')
		{
		//Delete then select the previous record
		mysql_query("DELETE FROM $table WHERE id = '$id'");
		mysql_query("DELETE FROM $mtable WHERE jobno = '$no' AND pn = '$linkvalue'");
		mysql_query("DELETE FROM $stable WHERE jobno = '$no' AND pn = '$linkvalue'");
		}
		$sql = "SELECT * FROM $table WHERE pn = '$linkvalue' ORDER BY $primaryfield";
		$msql = "SELECT * FROM $mtable WHERE pn = '$linkvalue' ORDER BY $mprimaryfield";
		$ssql = "SELECT * FROM $stable WHERE pn = '$linkvalue' ORDER BY $sprimaryfield";
		break;
	case "mNew":
		//Get Material Specification
		$mat = mysql_real_escape_string($_POST['nmat']);
		$cert = mysql_fetch_array(mysql_query("SELECT spec FROM material WHERE no = '$mat'"));
		$cert = $cert['spec'];
		//Get Number value for material list
		//See if the material is already on the list
		$material = mysql_fetch_array(mysql_query("SELECT no FROM $mtable WHERE pn = '$linkvalue' AND mat = '$mat'"));
		$material = $material['no'];
		if(!$material){
			$no = mysql_fetch_array(mysql_query("SELECT MAX(no) AS maxno FROM $mtable WHERE pn = '$linkvalue'"));
			$no = $no['maxno'] + 1;
		}
		else
			$no = $material;
		//Insert New Line Date
		//Get Job Number variable
		$jobno = mysql_real_escape_string($_POST['mline']);
		//Check to see if material is in the system and alert the user
		if(!$cert){
			mysql_query("INSERT INTO $mtable (pn,no,jobno,mat,unit) VALUES ('$linkvalue','$no','$jobno','$mat','Not In Sys')");
		}
		else
			mysql_query("INSERT INTO $mtable (pn,no,jobno,mat,cert) VALUES ('$linkvalue','$no','$jobno','$mat','$cert')");
		
		//Update Mat/Spec Info for Job Description
		$matnos = mysql_query("SELECT no FROM $mtable WHERE pn = '$linkvalue' AND jobno = '$jobno' ORDER BY no");
		while ($matrow = mysql_fetch_array($matnos)){
			$matspec .= $matrow['no'] . "-";
		}
		$specnos = mysql_query("SELECT let FROM $stable WHERE pn = '$linkvalue' AND jobno = '$jobno' ORDER BY let");
		while ($specrow = mysql_fetch_array($specnos)){
			$matspec .= $specrow['let'] . "-";
		}
		$matspec = substr_replace($matspec,"",-1);
		//Add Mat/Spec Info into Job Description
		mysql_query("UPDATE $table SET matspec = '$matspec' WHERE pn = '$linkvalue' AND no = '$jobno'");
		//Update all page info
		$sql = "SELECT * FROM $table WHERE pn = '$linkvalue' ORDER BY $primaryfield";
		$msql = "SELECT * FROM $mtable WHERE pn = '$linkvalue' ORDER BY $mprimaryfield";
		$ssql = "SELECT * FROM $stable WHERE pn = '$linkvalue' ORDER BY $sprimaryfield";
		break;
	case "mupdate":
		//Defind Variables
		$id = mysql_real_escape_string($_POST['mid']);
		$qty = mysql_real_escape_string($_POST['qty']);
		$unit = mysql_real_escape_string($_POST['unit']);
		//Update Date
		mysql_query("UPDATE $mtable SET qty = '$qty', unit = '$unit' WHERE id = '$id'");
		//Update all page info
		$sql = "SELECT * FROM $table WHERE pn = '$linkvalue' ORDER BY $primaryfield";
		$msql = "SELECT * FROM $mtable WHERE pn = '$linkvalue' ORDER BY $mprimaryfield";
		$ssql = "SELECT * FROM $stable WHERE pn = '$linkvalue' ORDER BY $sprimaryfield";
		break;
	case "mDelete":
		//Define Variables
		$id = mysql_real_escape_string($_POST['mid']);
		//Update Totals
		mysql_query("DELETE FROM $mtable WHERE id = '$id'");
		//Get Job Number variable
		$jobno = mysql_real_escape_string($_POST['mline']);
		//Update Mat/Spec Info for Job Description
		$matnos = mysql_query("SELECT no FROM $mtable WHERE pn = '$linkvalue' AND jobno = '$jobno' ORDER BY no");
		while ($matrow = mysql_fetch_array($matnos)){
			$matspec .= $matrow['no'] . "-";
		}
		$specnos = mysql_query("SELECT let FROM $stable WHERE pn = '$linkvalue' AND jobno = '$jobno' ORDER BY let");
		while ($specrow = mysql_fetch_array($specnos)){
			$matspec .= $specrow['let'] . "-";
		}
		$matspec = substr_replace($matspec,"",-1);
		//Add Mat/Spec Info into Job Description
		mysql_query("UPDATE $table SET matspec = '$matspec' WHERE pn = '$linkvalue' AND no = '$jobno'");
		//Update all page info
		$sql = "SELECT * FROM $table WHERE pn = '$linkvalue' ORDER BY $primaryfield";
		$msql = "SELECT * FROM $mtable WHERE pn = '$linkvalue' ORDER BY $mprimaryfield";
		$ssql = "SELECT * FROM $stable WHERE pn = '$linkvalue' ORDER BY $sprimaryfield";
		break;
	case "sNew":
		//Get Variables
		$spec = mysql_real_escape_string($_POST['spec']);
		$des = mysql_real_escape_string($_POST['des']);
		$jobno = mysql_real_escape_string($_POST['sline']);
		//Get Letter value for Spec list
		//See if the specification is already on the list
		//Check for Supp. Drawings duplicate spec value
		if($des == 'Drawing' || $des == 'Supp Drawing')
		{
			$specification = mysql_fetch_array(mysql_query("SELECT let FROM $stable WHERE pn = '$linkvalue' AND spec = '$spec' AND des = '$des'"));
		}
		else
		{
			$specification = mysql_fetch_array(mysql_query("SELECT let FROM $stable WHERE pn = '$linkvalue' AND spec = '$spec'"));
		}
		$specification = $specification['let'];
		//Create New Letter for Spec
		if(!$specification){
			$let = mysql_fetch_array(mysql_query("SELECT let FROM $stable WHERE pn = '$linkvalue 'ORDER BY LENGTH(let) DESC, let DESC LIMIT 1"));
			$let = $let['let'];
			if($let)
				$let++;
			else
				$let = "A";
		}
		else
			$let = $specification;
		//Insert New Line Data
		mysql_query("INSERT INTO $stable (pn,let,jobno,spec,des) VALUES ('$linkvalue','$let','$jobno','$spec','$des')");
		//Update Mat/Spec Info for Job Description
		$matnos = mysql_query("SELECT no FROM $mtable WHERE pn = '$linkvalue' AND jobno = '$jobno' ORDER BY no");
		while ($matrow = mysql_fetch_array($matnos)){
			$matspec .= $matrow['no'] . "-";
		}
		$specnos = mysql_query("SELECT let FROM $stable WHERE pn = '$linkvalue' AND jobno = '$jobno' ORDER BY let");
		while ($specrow = mysql_fetch_array($specnos)){
			$matspec .= $specrow['let'] . "-";
		}
		$matspec = substr_replace($matspec,"",-1);
		//Add Mat/Spec Info into Job Description
		mysql_query("UPDATE $table SET matspec = '$matspec' WHERE pn = '$linkvalue' AND no = '$jobno'");
		//Update all page info
		$sql = "SELECT * FROM $table WHERE pn = '$linkvalue' ORDER BY $primaryfield";
		$msql = "SELECT * FROM $mtable WHERE pn = '$linkvalue' ORDER BY $mprimaryfield";
		$ssql = "SELECT * FROM $stable WHERE pn = '$linkvalue' ORDER BY $sprimaryfield";
		break;


//This is where you left off
	case "supdate":
		//Define Variables
		$id = mysql_real_escape_string($_POST['sid']);
		$des = mysql_real_escape_string($_POST['des']);
		//Update Query
		mysql_query("UPDATE $stable SET des = '$des' WHERE id = '$id'");
		//Update all page info
		$sql = "SELECT * FROM $table WHERE pn = '$linkvalue' ORDER BY $primaryfield";
		$msql = "SELECT * FROM $mtable WHERE pn = '$linkvalue' ORDER BY $mprimaryfield";
		$ssql = "SELECT * FROM $stable WHERE pn = '$linkvalue' ORDER BY $sprimaryfield";
		break;
	case "sDelete":
		//Define Variables
		$id = mysql_real_escape_string($_POST['sid']);
		//Update Totals
		mysql_query("DELETE FROM $stable WHERE id = '$id'");
		//Get Job Number variable
		$jobno = mysql_real_escape_string($_POST['sline']);
		//Update Mat/Spec Info for Job Description
		$matnos = mysql_query("SELECT no FROM $mtable WHERE pn = '$linkvalue' AND jobno = '$jobno' ORDER BY no");
		while ($matrow = mysql_fetch_array($matnos)){
			$matspec .= $matrow['no'] . "-";
		}
		$specnos = mysql_query("SELECT let FROM $stable WHERE pn = '$linkvalue' AND jobno = '$jobno' ORDER BY let");
		while ($specrow = mysql_fetch_array($specnos)){
			$matspec .= $specrow['let'] . "-";
		}
		$matspec = substr_replace($matspec,"",-1);
		//Add Mat/Spec Info into Job Description
		mysql_query("UPDATE $table SET matspec = '$matspec' WHERE pn = '$linkvalue' AND no = '$jobno'");
		//Update all page info
		$sql = "SELECT * FROM $table WHERE pn = '$linkvalue' ORDER BY $primaryfield";
		$msql = "SELECT * FROM $mtable WHERE pn = '$linkvalue' ORDER BY $mprimaryfield";
		$ssql = "SELECT * FROM $stable WHERE pn = '$linkvalue' ORDER BY $sprimaryfield";
		break;
	case "load":
	case "menu":
	case "Delete JSI":
	case "New JSI":
		//Initial load of Form
		$limit = 0;
		$sql = "SELECT * FROM $table WHERE pn = '$linkvalue' ORDER BY $primaryfield";
		$msql = "SELECT * FROM $mtable WHERE pn = '$linkvalue' ORDER BY $mprimaryfield";
		$ssql = "SELECT * FROM $stable WHERE pn = '$linkvalue' ORDER BY $sprimaryfield";
		break;
}
//create query
$result = mysql_query($sql);
if(mysql_num_rows($result)==0) 
	echo "No Line Items Entered";

?>
	<form id="lineitems">
	<table>
	<tr>
	<th width="2">No</th>
	<th width="150">Job Description</th>
	<th>Mat/Spec</th>
	</tr>

<?php
for ($i = 0;$row = mysql_fetch_array($result); $i++)
{
?>
	<tr>
	<input type="hidden" id="<?php echo $i;?>" name="id" value="<?php echo $row['id'];?>"/>
	<td><input type="text" id="<?php echo $i;?>" name="no" size="2" value="<?php echo $row['no'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="job" size="30" value="<?php echo $row['job'];?>" onchange="UpdateLine('update',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="matspec" size="10" value="<?php echo $row['matspec'];?>"/></td>
	<td><input type="button" id="<?php echo $i;?>" name="delete" value="Delete" onclick="UpdateLine(this.value,this)"/></td>
	</tr>
<!-- Materials Section--!>
		<tr>
		<td></td>
		<td></td>
		<th>Material</th>
		<th>Qty</th>
		<th>Unit</th>
		<th></th>
		</tr>
		<?php
		$rowline = $row['no'];
		$mresult = mysql_query($msql);
		for ($j = $i + $j;$mrow = mysql_fetch_array($mresult); $j++)
		{
		if($mrow['jobno'] == $rowline)
		{
		?>
		<input type="hidden" id="<?php echo $j;?>" name='mline' value="<?php echo $rowline;?>"/>
		<input type="hidden" id="<?php echo $j;?>" name='mid' value="<?php echo $mrow['id'];?>"/>
		<tr>
		<td></td>
		<td></td>
		<td><input type="text" id="<?php echo $j;?>" name="mat" size="10" value="<?php echo $mrow['mat'];?>"/></td>
		<td><input type="text" id="<?php echo $j;?>" name="qty" size="4" value="<?php echo $mrow['qty'];?>" onchange="UpdateLine('mupdate',this)"/></td>
		<td><input type="text" id="<?php echo $j;?>" name="unit" size="10" value="<?php echo $mrow['unit'];?>" onchange="UpdateLine('mupdate',this)"/></td>
		<td><input type="button" id="<?php echo $j;?>" name="mdelete" value="Delete" onclick="UpdateLine('mDelete',this)"/></td>
		</tr>
		<?php
		}
		}?>
		<tr>
		<input type="hidden" id="<?php echo $j;?>" name='mline' value="<?php echo $rowline;?>"/>
		<td></td>
		<td></td>
		<td><input type="text" id="<?php echo $j;?>" name="nmat" size="10" onchange="UpdateLine('mNew',this)"/></td>
		<td><input type="text" id="<?php echo $j;?>" name="nqty" size="4"/></td>
		<td><input type="text" id="<?php echo $j;?>" name="nunit" size="10"/></td>
		</tr>
<!-- Specifiactions Section--!>
		<tr>
		<td></td>
		<td></td>
		<th>Specification</th>
		<th colspan="2">Description</th>
		<td></td>
		</tr>
		<?php
		$rowline = $row['no'];
		$sresult = mysql_query($ssql);
		for ($k = $i + $k;$srow = mysql_fetch_array($sresult); $k++)
		{
		if($srow['jobno'] == $rowline)
		{
		?>
		<input type="hidden" id="<?php echo $k;?>" name='sline' value="<?php echo $rowline;?>"/>
		<input type="hidden" id="<?php echo $k;?>" name='sid' value="<?php echo $srow['id'];?>"/>
		<tr>
		<td></td>
		<td></td>
		<td><input type="text" id="<?php echo $k;?>" name="spec" size="10" value="<?php echo $srow['spec'];?>"/></td>
		<td colspan="2"><input type="text" id="<?php echo $k;?>" name="des" size="20" value="<?php echo $srow['des'];?>" onchange="UpdateLine('supdate',this)"/></td>
		<td><input type="button" id="<?php echo $k;?>" name="sdelete" value="Delete" onclick="UpdateLine('sDelete',this)"/></td>
		</tr>
		<?php
		}
		}?>
		<tr>
		<input type="hidden" id="<?php echo $k;?>" name='sline' value="<?php echo $rowline;?>"/>
		<td></td>
		<td></td>
		<td><input type="text" id="<?php echo $k;?>" name="spec" size="10" onkeyup="LiveSearch(this, '<?php echo $rowline;?>')"/></td>
		<td colspan="2"><input type="text" id="<?php echo $k;?>" name="des" size="20"/></td>
		<td></td>
		</tr>
		<tr>
			<td colspan="5">
				<div style="background: white;position: relative;" id="<?php echo 'search'.$rowline;?>" name="search">
			</td>
			</div>
		</tr>
<?php
}
	$maxsql = "SELECT MAX(no) AS maxno FROM $table WHERE pn = '$linkvalue'";
	$maxresult = mysql_query($maxsql);
	$maxrow = mysql_fetch_assoc($maxresult);
	$maxline = $maxrow['maxno'] + 1;
	$i = $i + 1;
?>
	</table>
	<table>
	<tr>
	<td><input type="text" id="<?php echo $i;?>" name="no" size="2" value="<?php echo $maxline;?>"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="job" size="30" onchange="UpdateLine('New',this)"/></td>
	<td><input type="text" id="<?php echo $i;?>" name="matspec" size="10"/></td>
	</tr>
	</table>
	</form>
<?php
mysql_close($con);
?>
</body>
</html>
