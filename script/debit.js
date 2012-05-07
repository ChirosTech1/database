//<![CDATA[
//Update contact information
/*var formtype = "PO";
var mainfile = "purchasing";
var mainform = "gdebit.php";
var lineform = "gldebit.php";
var noteform = "gndebit.php";
var printform = "pdebit.php";
/*function UpdateForm(q,id,idname)
{
	var xhr = GetObject();
	//define variables
	var pinfo = "q="+q + FormValues();
	var url = "../purchasing/gdebit.php";
	if (q == 'Delete PO')
	{
		if(confirm("Are you sure you want to Delete this Debit Memo?"))
		{
		AjaxCall(xhr,url,pinfo);
		}
	}
	else if (q == 'New PO')
	{
		if(confirm("Are you sure you want to Add a new Debit Memo?"))
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
			if (q!='price')
			{
				if (q!='update')
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
	var url = "../purchasing/gldebit.php";
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
	var xhr2 = GetObject();
	//define variables
	var pinfo = "q="+q + "&linkvalue=" + linkvalue + NoteValues(id);
	var url = "../purchasing/gndebit.php";
	if (q == 'Delete')
	{
		if(confirm("Are you sure you want to Delete this Note?"))
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
			document.getElementById("note").innerHTML=xhr2.responseText;
			switch(q)
			{
			case 'update':
			case 'taxable':
			case 'Delete':
			UpdateForm('price');
			break;
			}
		}
	}

}
function RevDate(q)
{
	document.getElementById('date').value = q;
}
function NextField(c)
{
	var elemen = document.getElementById('lineitems').elements;
	var nfield = 0;
	
	for(var i = elemen.length; elemen[i].id != c; i++)
	{
		nfield = elemen[i].name;
		
	}
	document.getElementById(nfield).focus();
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
*/
//]]
