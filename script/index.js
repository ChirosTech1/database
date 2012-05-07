//<![CDATA[
function direct(dir)
{
	var xhr = GetObject();
	var url = "../index/index.php";
	
	var pinfo = "q="+dir + FormValues('main');
	//retrieve information
	xhr.open("POST", url, true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.setRequestHeader("Connection", "close");
	xhr.send(pinfo);
	xhr.onreadystatechange = null;
	xhr.onreadystatechange = function()
	{
		if (xhr.readyState==4 && xhr.status==200)
		{
		document.getElementById("items").innerHTML=xhr.responseText;
		}
	}
}
//]]
