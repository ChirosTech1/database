//<![CDATA[
function UpdateForm(q,id,idname)
{
	var xhr = GetObject();
	//define variables
	var pinfo = "q="+q + FormValues('main');
	var url = "../" + mainfile + "/" + mainform;
	if (q == 'Delete ' + formtype)
	{
		if(confirm("Are you sure you want to Delete this " + formtype + "?"))
		{
		AjaxCall(xhr,url,pinfo);
		}
	}
	else if (q == 'New PO')
	{
		if(confirm("Are you sure you want to Add a new " + formtype + "?"))
		{
			if(confirm("Is this a Blanket " + formtype + "?"))
			{
			var blanket = prompt ("What is the Blanket " + formtype + " Number?");
			pinfo = "q=blanket&blanket=" + blanket + FormValues();
			AjaxCall(xhr,url,pinfo);
			}
			else
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
			   var x = elem[i].name;
			   //gets JSON data based on elements on html form
			   var data = jsondata[0][x];
		 	   //insert data into the form
			   document.getElementsByName(x)[0].value = data;
			   }
			if (q!='menu')
			   document.getElementsByName('menu')[0].value = 'default';
			if (q!='price' && q!='update' && q!='Close')
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
	var linkvalue = document.getElementsByName('no')[0].value;
	var callvalue = document.getElementsByName('callno')[0].value;
	var xhr2 = GetObject();
	//define variables
	var pinfo = "q="+q + "&linkvalue=" + linkvalue + "&callvalue=" + callvalue + FormValues('lineitems',id);
	var url = "../" + mainfile + "/" + lineform;
	if (q == 'Delete')
	{
		if(confirm("Are you sure you want to Delete this Line?"))
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
		if(q != 'update')
		{
			if(q == 'New PO' || q == 'New')
			{
			EditForm(q);
			}
			else
			EditForm();
		}

	}

}
function UpdateNote(q,id)
{
	var linkvalue = document.getElementsByName('no')[0].value;
	var callvalue = document.getElementsByName('callno')[0].value;
	var xhr3 = GetObject();
	//define variables
	var pinfo = "q="+q + "&linkvalue=" + linkvalue + "&callvalue=" + callvalue + FormValues('notes',id);
	var url = "../" + mainfile + "/" + noteform;
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

		}
		if(q != 'update')
		{
			if(q == 'New PO' || q == 'New')
			{
			EditForm(q);
			}
			else
			EditForm();
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
		if(elem[i].checked == true)
		pinfo += "&" + elem[i].id + "=" + elem[i].value;
		else
		pinfo += "&" + elem[i].id + "=0";
	}
	if(q)
	window.open('../' + mainfile + '/' + printform + '?id=' + id + "&print=" + q + pinfo,'Purchase Order Form','left=20,top=20,width=1000,height=500,toolbar=1,scrollbars=yes,resizable=0');
	else
	window.open('../' + mainfile + '/' + printform + '?id=' + id + pinfo,'Purchase Order Form','left=20,top=20,width=1000,height=500,toolbar=1,scrollbars=yes,resizable=0');
}

function FormValues(type,id)
{
	//Define variables
	var pinfo = '';
	//retrieves all elements from form 'main'
	var elem = document.getElementById(type).elements;
	//add form info to pinfo variable
	for(var i = 0; i < elem.length; i++)
	{
		//create pinfo string for ajax use
		if(id)
		{
			if(elem[i].id == id)
			pinfo += "&" + elem[i].name + "=" + elem[i].value;
		}
		else
		{
			if(elem[i].value)
			pinfo += "&" + elem[i].name + "=" + elem[i].value;
		}
	}
	return (pinfo);
}
function NextField(id,idname)
{
/*	var test = document.getElementById(id).item(1);
	alert(test);
	if(id)
	{   
        var arr = new Array();
//        arr = document.getElementsByName(objName);
	arr = document.getElementById('lineitems').elements;
        var obj = 0;    
        for(var i = 0; obj.name != idname; i++)
        {
            var obj = document.getElementById(id).item(i);
        }
//        alert(obj.id + " =  " + obj.value);
	var obj = document.getElementById(id).item(i);
	if(obj)
	obj.focus();
	}*/
	//Get all the input elements
	var elem = document.getElementsByTagName("input");
	for (var i = 0; i < elem.length; i++)
	{
		if (elem[i].id == id)
		{
			if(elem[i].name == idname)
			var obj = document.getElementsByName(idname)[2];
		}
	}
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
		}
	}
}
//]]
