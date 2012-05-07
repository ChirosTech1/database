//<![CDATA[
function UpdateForm(q,live)
{
	var xhr = GetObject();
	//define variables
	if(live)
	var pinfo = "q="+q + "&live=" + live + FormValues('main');
	else
	var pinfo = "q="+q + FormValues('main');
	var url = "../" + mainfile + "/" + mainform;
	//Delete Something
	if (q == 'Delete ' + formtype)
	{
		if(confirm("Are you sure you want to Delete this " + formtype + "?"))
		{
		AjaxCall(xhr,url,pinfo);
		}
	}
	//New Something
	else if (q == 'New ' + formtype)
	{
		if(confirm("Are you sure you want to Add a new " + formtype + "?"))
		{
			if(mainform == 'glongpo.php')
			{
				if(confirm("Is this a Blanket " + formtype + "?"))
				{
				var blanket = prompt ("What is the Blanket " + formtype + " Number?");
				pinfo = "q=blanket&blanket=" + blanket + FormValues('main');
				AjaxCall(xhr,url,pinfo);
				}
				else
				AjaxCall(xhr,url,pinfo);
			}
			else if (q == "New Material")
			{
				var material = prompt ("What is the New Material Number?");
				if(material)
				{
					pinfo = "q="+q+"&material="+material;
					AjaxCall(xhr,url,pinfo);
				}	
			}
			else if (q == "New Part Number")
			{
				var pn = prompt ("What is the New Part Number?");
				if(pn)
				{
					pinfo = "q="+q+"&pn="+pn;
					AjaxCall(xhr,url,pinfo);
				}	
			}

			else
			AjaxCall(xhr,url,pinfo);
		}
		else
		window.close();
	}
	//New Material
	else if (q == "New Material")
	{
		var material = prompt ("What is the New Material Number?");
		if(material)
		{
			pinfo = "q="+q+"&material="+material;
			AjaxCall(xhr,url,pinfo);
		}
	}
	//Everything Else
	else
	{
		AjaxCall(xhr,url,pinfo);
	}
	xhr.onreadystatechange = function ()
	{
		if (xhr.readyState==4 && xhr.status==200)
		{
			//Parse JSON Data
			var jsondata = JSON.parse(xhr.responseText);
			//Retrieve all form elements and data
			var elem = document.getElementById('main').elements;
			if(jsondata)
			{
			   //Insert the updated info into html form
			   for (var i = 0; i < elem.length; i++)
		 	   {
			   //gets ids for all elements on html form
			   var x = elem[i].name;
			   //gets JSON data based on elements on html form
			   var data = jsondata[0][x];
		 	   //insert data into the form
			   if(elem[i].name != 'livesearch')
			   document.getElementsByName(x)[0].value = data;
			//Live Search Update
			if (live)
			{
	    		   document.getElementById("search").innerHTML="";
		 	   document.getElementById("search").style.border="0px";
			}
			   }
			//Menu Update
			if (q!='menu')
			   {
			   document.getElementsByName('menu')[0].value = 'default';
			   }
			//Update lines and notes
			if (q!='price' && q!='update' && q!='Close' && q != 'cload')
			{
				if(lineform)
				{
					UpdateLine(q);
				}
				if(noteform)
				{
					UpdateNote(q);
				}
			}
			}
			else
			alert ("No Records to Display!!!");
		}
		if(q == 'cload')
		{
			EditForm();
		}
	}

}
function UpdateLine(q,e)
{
	if(e)
	{
	var id = e.id;
	}
	else
	{
	var id = 0;
	}
	//Get call no if long po
	if(mainform == 'glongpo.php')
		var callvalue = document.getElementsByName('callno')[0].value;
	var linkvalue = document.getElementsByName('no')[0].value;
	var xhr2 = GetObject();
	var callvalue;
	//define variables
	if(callvalue)
	var pinfo = "q="+q + "&linkvalue=" + linkvalue + "&callvalue=" + callvalue + FormValues('lineitems',id);
	else
	var pinfo = "q="+q + "&linkvalue=" + linkvalue + FormValues('lineitems',id);
	var url = "../" + mainfile + "/" + lineform;
	if (q == 'Delete')
	{
		if(confirm("Are you sure you want to Delete this Line?"))
		{
		AjaxCall(xhr2,url,pinfo);
		}
	}
	else if (q == 'Delete' + formtype)
	{
		if(confirm("Are you sure you want to Delete this " + formtype +"?"))
		{
		AjaxCall(xhr2,url,pinfo);
		}
	}
	else
	{
		AjaxCall(xhr2,url,pinfo);
	}
	xhr2.onreadystatechange = function ()
	{
		if (xhr2.readyState==4 && xhr2.status==200)
		{
			document.getElementById("lineitem").innerHTML=xhr2.responseText;
			//Focus on next DOM element
			if(e)
			{
				var elem = document.getElementById('lineitems').elements;
				var obj = 0;
				for (var i = 0; i < elem.length; i++)
				{
					if(lineform == "glwo.php" && elem[i].name == 'rev')
					{
						if(elem[i].id == e.id)
						{
							if(elem[i].name == e.name)
							{
							i = i + 2;
							var elid = elem[i].id;
							var obj = document.getElementsByName(elem[i].name)[elid];
							}
						}
					}
					else
					{
						if(elem[i].id == e.id)
						{
							if(elem[i].name == e.name)
							{
							i++;
							var elid = elem[i].id;
							var obj = document.getElementsByName(elem[i].name)[elid];
							}
						}
					}
				}
				if(obj)
				obj.focus();
			}
			switch(q)
			{
			case 'update':
			case 'dupdate':
			case 'taxable':
			case 'Delete':
			case 'dDelete':
			UpdateForm('price');
			break;
			}
		}
		if(q != 'update' && q != 'taxable' && q != 'early' && q != 'Delete' && q != 'dDelete' && q != 'dupdate')
		{
			if(q == 'New ' + formtype || q == 'New' || q == 'dNew')
			{
			EditForm(q);
			}
			else
			EditForm();
		}

	}

}
function UpdateNote(q,e)
{
	if(e)
		var id = e.id;
	else
		var id = 0;
	//Get call no if long po
	if(mainform == 'glongpo.php')
		var callvalue = document.getElementsByName('callno')[0].value;
	var linkvalue = document.getElementsByName('no')[0].value;
	var callvalue;
	var xhr4 = GetObject();
	//define variables
	if(callvalue)
	var pinfo = "q="+q + "&linkvalue=" + linkvalue + "&callvalue=" + callvalue + FormValues('notes',id);
	else
	var pinfo = "q="+q + "&linkvalue=" + linkvalue + FormValues('notes',id);
	var url = "../" + mainfile + "/" + noteform;
	if (q == 'Delete')
	{
		if(confirm("Are you sure you want to Delete this Note?"))
		{
		AjaxCall(xhr4,url,pinfo);
		}
	}
	else
	{
		AjaxCall(xhr4,url,pinfo);
	}
	xhr4.onreadystatechange = function ()
	{
		if (xhr4.readyState==4 && xhr4.status==200)
		{
			document.getElementById("note").innerHTML=xhr4.responseText;
			if(e)
			{
				var elem = document.getElementById('notes').elements;
				var obj = 0;
				for (var i = 0; i < elem.length; i++)
				{
					if(elem[i].id == e.id)
					{
						if(elem[i].name == e.name)
						{
						i++
						i++
						var elid = elem[i].id;
						var obj = document.getElementsByName(elem[i].name)[elid];
						}
					}
				}
				obj.focus();
			}
		}
		if(q != 'update')
		{
			if(q == 'New ' + formtype || q == 'New')
			{
			EditForm(q);
			}
			else
			EditForm();
		}
	}

}
function ApproveForm()
{
	var qcid = prompt("Please enter your QC stamp number.");
	if(!qcid)
	qcid = 0;
	var xhr = GetObject();
	//define variables
	var pinfo = "q=Approve" + "&qcid=" + qcid + FormValues('main');
	var url = "../" + mainfile + "/" + mainform;
	AjaxCall(xhr,url,pinfo);
	xhr.onreadystatechange = function()
	{
		if (xhr.readyState==4 && xhr.status==200)
		{
			//Parse JSON Data
			var jsondata = JSON.parse(xhr.responseText);
			//Retrieve all form elements and data
			var elem = document.getElementById('main').elements;
			if(jsondata)
			{
			   //Insert the updated info into html form
			   for (var i = 0; i < elem.length; i++)
		 	   {
			   //gets JSON data based on elements on html form
			   var data = jsondata[0].qcapprove;
		 	   //insert data into the form
			   document.getElementsByName('qcapprove')[0].value = data;
			   }
			   if(data)
			   alert ("Order Approved!!!");
			   else
			   alert ("Order Not Approved!!!");
			}
		}
	}
}
function RevDate(q)
{
	document.getElementById('date').value = q;
}
function PrintForm(q)
{
	var id = document.getElementById('id').value;
	var pinfo = '';
	var elem = document.getElementById('printform').elements;
	for(var i = 0; i < elem.length; i++)
	{
		if(elem[i].checked == true && elem[i].id)
		pinfo += "&" + elem[i].id + "=" + elem[i].value;
		else if(elem[i].id)
		pinfo += "&" + elem[i].id + "=0";
	}
	if(printform == 'pcertpo.php' && q)
	{
		if(document.getElementsByName('ordered')[0].value)
			window.open('../' + mainfile + '/' + printform + '?id=' + id + "&print=" + q + pinfo,'Purchase Order Form','left=20,top=20,width=1000,height=500,toolbar=1,scrollbars=yes,resizable=0');
		else
		{
			alert("Order must be approved before printing!");
		}
	}
	else
	{
		if(q)
		{
			window.open('../' + mainfile + '/' + printform + '?id=' + id + "&print=" + q + pinfo,'Purchase Order Form','left=20,top=20,width=1000,height=500,toolbar=1,scrollbars=yes,resizable=0');
		}
		else
		window.open('../' + mainfile + '/' + printform + '?id=' + id + pinfo,'Purchase Order Form','left=20,top=20,width=1000,height=500,toolbar=1,scrollbars=yes,resizable=0');
	}
}
function FormValues(type,id)
{
	//Define variables
	var pinfo = '';
	//retrieves all elements from form 'main'
	if(document.getElementById(type))
	{
	var elem = document.getElementById(type).elements;
	}
	else
	var elem = 0;
	//add form info to pinfo variable
	for(var i = 0; i < elem.length; i++)
	{
		//create pinfo string for ajax use
		if(id)
		{
			if(elem[i].id == id)
			pinfo += "&" + elem[i].name + "=" + encodeURIComponent(elem[i].value);
		}
		else
		{
			if(elem[i].value)
			pinfo += "&" + elem[i].name + "=" + encodeURIComponent(elem[i].value);
		}
	}
	return (pinfo);
}
function EditForm(q)
{
	//Get all the input elements in an array
	var elem = document.getElementsByTagName("input");
	//see if a value was passed
	if(q)
	{
		//loop through all the elements and make them not readonly
		for(i = 0; i < elem.length; i++)
		{
			//Keep Totals Readonly
			if(elem[i].name != "subtot" && elem[i].name != "taxtot" && elem[i].name != "tot")
			elem[i].readOnly = false;
			if(document.getElementsByName('job')[0])
			document.getElementsByName('job')[0].disabled = false;
		}
	}
	else
	{
		//Make all text input elements readonly
		for(i = 0; i < elem.length; i++)
		{
			if(elem[i].type == "text")
			elem[i].readOnly = true;
			if(document.getElementsByName('job')[0])
			document.getElementsByName('job')[0].disabled = true;
		}
	}
}
function GetObject()
{	
	if (window.XMLHttpRequest)
		return new XMLHttpRequest();
	else
		return new ActiveXObject("Microsoft.XMLHTTP");
}
function AjaxCall(xhr,url,pinfo)
{
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.setRequestHeader("Connection", "close");
	xhr.send(pinfo);

}
function LiveSearch(q)
{
	var search = q.value;
	var xhr = GetObject();
	//define variables
	if(field3)
	var pinfo = "search="+search+"&table="+table+"&field="+field+"&field2="+field2+"&field3="+field3;
	else
	var pinfo = "search="+search+"&table="+table+"&field="+field+"&field2="+field2;
	var url = "../" + mainfile + "/" + liveform;
	if(search.length == 0)
		{
		document.getElementById("search").innerHTML="";
		document.getElementById("search").style.border="0px";
		return;
		}
	else
	AjaxCall(xhr,url,pinfo);
	xhr.onreadystatechange = function ()
	{
		if (xhr.readyState==4 && xhr.status==200)
		{
		document.getElementById("search").innerHTML=xhr.responseText;
		document.getElementById("search").style.border="1px solid #A5ACB2";
		}
	}
}
function NewLine(f1,f2,f3)
{
	var nline = document.getElementsByName(field).length - 1;
	var elem = document.getElementsByName(field)[nline];
	document.getElementsByName(field)[nline].value = f1;
	document.getElementsByName(field2)[nline].value = f2;
	if(f3 && field3)
	document.getElementsByName(field3)[nline].value = f3;
	UpdateLine('New', elem);
	if(formtype == "History")
	document.getElementById('search').innerHTML = null;
}
//]]
