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
			if (q == "New JSI")
			{
				var pn = prompt ("What is the New JSI Part Number?");
				if(pn)
				{
					pinfo = "q="+q+"&menu="+pn;
					AjaxCall(xhr,url,pinfo);
				}	
			}
			else
			AjaxCall(xhr,url,pinfo);
		}
		else
		window.close();
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
	//Grab the linkvalue
	var linkvalue = document.getElementsByName('no')[0].value;
	var xhr2 = GetObject();
	//define variables
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
		}
		if(q != 'update' && q != 'mupdate' && q != 'supdate' && q != 'Delete' && q != 'dDelete' && q != 'dupdate' && q != 'New')
		{
			if(q == 'New' + formtype || q == 'mNew' || q == 'sNew')
			{
			EditForm(q);
			}
			else
			EditForm();
		}

	}

}
function PrintForm(q)
{
	var id = document.getElementById('id').value;
		if(q)
		{
			window.open('../' + mainfile + '/' + printform + '?id=' + id + "&print=" + q, formtype + ' Form','left=20,top=20,width=1000,height=500,toolbar=1,scrollbars=yes,resizable=0');
		}
		else
			window.open('../' + mainfile + '/' + printform + '?id=' + id, formtype + ' Form','left=20,top=20,width=1000,height=500,toolbar=1,scrollbars=yes,resizable=0');
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
function LiveSearch(q,row)
{
	var search = q.value;
	var xhr = GetObject();
	//define variables
	var pinfo = "search="+search+"&field="+field+"&row="+row;
	var url = "../" + mainfile + "/" + liveform;
	if(search.length == 0)
		{
		document.getElementById("search"+row).innerHTML="";
		document.getElementById("search"+row).style.border="0px";
		return;
		}
	else
	AjaxCall(xhr,url,pinfo);
	xhr.onreadystatechange = function ()
	{
		if (xhr.readyState==4 && xhr.status==200)
		{
		document.getElementById("search"+row).innerHTML=xhr.responseText;
		document.getElementById("search"+row).style.border="1px solid #A5ACB2";
		}
	}
}
function NewLine(f1,f2,row)
{
	var nline = document.getElementsByName("spec").length - 1;
	var elem = document.getElementsByName("spec")[nline];
	document.getElementsByName("spec")[nline].value = f1;
	document.getElementsByName(field2)[nline].value = f2;
	document.getElementsByName("sline")[nline].value = row;
	UpdateLine('sNew', elem);
}
//]]
