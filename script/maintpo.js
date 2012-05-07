//<![CDATA[
//Update contact informationp
var formtype = "PO";
var mainfile = "maintenance";
var mainform = "gmaintpo.php";
var lineform = "glmaintpo.php";
var noteform = "gnmaintpo.php";
var printform = "pmaintpo.php";
/*function UpdateForm(q,id,idname)
{
	var xhr = GetObject();
	//define variables
	var pinfo = "q="+q + FormValues();
	var url = "../maintenance/gmaintpo.php";
	if (q == 'Delete PO')
	{
		if(confirm("Are you sure you want to Delete this PO?"))
		{
		AjaxCall(xhr,url,pinfo);
		}
	}
	else if (q == 'New PO')
	{
		if(confirm("Are you sure you want to Add a new PO?"))
		{
		AjaxCall(xhr,url,pinfo);
		}
		else
		window.close();
	}
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
			   var x = elem[i].id;
			   //gets JSON data based on elements on html form
			   var data = jsondata[0][x];
		 	   //insert data into the form
			   document.getElementById(x).value = data;
			   }
			if (q!='menu')
			document.getElementById('menu').value = 'default';
			if (q!='price' && q!='update')
			{
				UpdateLine(q);
				UpdateNote(q);
			}
			}
			else
			alert ("Congradulations!!! You have reached the end of the records!");
			NextField(id,idname);
		}
	}
}
function UpdateLine(q,id,idname)
{
	var linkvalue = document.getElementById('no').value;
	var xhr2 = GetObject();
	//define variables
	var pinfo = "q="+q + "&linkvalue=" + linkvalue + LineValues(id);
	var url = "../maintenance/glmaintpo.php";
	if (q == 'Delete')
	{
		if(confirm("Are you sure you want to Delete this Line Item?"))
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
			switch(q)
			{
			case 'update':
			case 'taxable':
			case 'Delete':
			UpdateForm('price',id,idname);
			break;
			}

		}
	}
}
function UpdateNote(q,id)
{
	var linkvalue = document.getElementById('no').value;
	var xhr3 = GetObject();
	//define variables
	var pinfo = "q="+q + "&linkvalue=" + linkvalue + NoteValues(id);
	var url = "../maintenance/gnmaintpo.php";
	if (q == 'Delete')
	{
		if(confirm("Are you sure you want to Delete this Note?"))
		{
		AjaxCall(xhr3,url,pinfo);
		}
	}
	else
	{
		AjaxCall(xhr3,url,pinfo);
	}
	xhr3.onreadystatechange = function ()
	{
		if (xhr3.readyState==4 && xhr3.status==200)
		{
			document.getElementById("note").innerHTML=xhr3.responseText;
			switch(q)
			{
			case 'update':
			case 'taxable':
			case 'Delete':
			UpdateForm('price');
			break;
			}
			if(q != 'update')
			{
				if(q == 'New PO')
				EditForm(q);
				else
				EditForm();
			}

		}
	}

}
function RevDate(q)
{
	document.getElementById('date').value = q;
}

function NextField(id,idname)
{
	if(id)
	{   
        var arr = new Array();
//        arr = document.getElementsByName(objName);
	arr = document.getElementById('lineitems').elements;
        var obj = 0;    
        for(var i = 0; obj.id != idname; i++)
        {
            var obj = document.getElementsByName(id).item(i);
        }
//        alert(obj.id + " =  " + obj.value);
	var obj = document.getElementsByName(id).item(i);
	if(obj)
	obj.focus();
	}
}
function PrintForm(q)
{
	var pono = document.getElementById('no').value;
	var pinfo = '';
	var elem = document.getElementById('printform').elements;
	for(var i = 0; i < elem.length; i++)
	{
		if(elem[i].checked == true)
		pinfo += "&" + elem[i].id + "=" + elem[i].value;
		else
		pinfo += "&" + elem[i].id + "=0";
	}
	if(q)
	window.open('../maintenance/pmaintpo.php?no=' + pono + "&print=" + q + pinfo,'Purchase Order Form','left=20,top=20,width=1000,height=500,toolbar=1,scrollbars=yes,resizable=0');
	else
	window.open('../maintenance/pmaintpo.php?no=' + pono + pinfo,'Purchase Order Form','left=20,top=20,width=1000,height=500,toolbar=1,scrollbars=yes,resizable=0');
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
			//Keep Total Prices readonly
			if(elem[i].id == "subtot"){}
			else if(elem[i].id == "taxtot"){}
			else if(elem[i].id == "tot"){}
			else
			elem[i].readOnly = false;
		}
	}
	else
	{
		//Make all text input elements readonly
		for(i = 0; i < elem.length; i++)
		{
			if(elem[i].type == "text")
			elem[i].readOnly = true;
			if(elem[i].id == "job")
			elem[i].disabled = true;
		}
	}
}
*/
//]]
