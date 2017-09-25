var primarySelect = [];
var secondarySelect = [];
var terciarySelect = [];
var cuaternarySelect = [];

function checkFilters()
{
	currentUrl = 'modedit.php?add=smartclassroom&type=&course=2&section=1&return=0&sr=0&step=".$nextstep."';
	valuePrimary = document.getElementById('id_scrprimary').value;
	valueSecondary = document.getElementById('id_scrsecondary').value;
	if ((valuePrimary != '')&&(valueSecondary != ''))
	{
		document.getElementById('id_scrterciary').innerHTML = '';
		for (i in terciarySelect)
		{
			var option = document.createElement("option");
			option.text = terciarySelect[i];
			//console.log(option.text);
			option.value = i;
			//console.log(option.value);
			document.getElementById('id_scrterciary').appendChild(option);
		}
		document.getElementById('id_scrterciary').removeAttribute('disabled');	
	} 
	/*else console.log('Todavía sin elección');*/

}

function fillCuaternary()
{
	var valueTerciary = document.getElementById('id_scrterciary').value;
	/*console.log('valueTerciary escogido es:' + valueTerciary);*/
	var possibleValues = cuaternarySelect[valueTerciary]; 

	document.getElementById('id_scrcuaternary').innerHTML = document.getElementById('id_scrcuaternary').firstElementChild.outerHTML;
	var keys = Object.keys(possibleValues);
		for (i=0 ; i < keys.length ; i++)
		{
			var key = keys[i];
			var option = document.createElement("option");
			option.text = possibleValues[key].title;
			/*console.log(option.text);*/
			option.value = possibleValues[key].url;
			/*console.log(option.value);*/
			document.getElementById('id_scrcuaternary').appendChild(option);
		}

	//document.getElementById('id_scrcuaternary').removeAttribute('disabled');	
}
function initSelectsContainers(yui3, terciary, cuaternary)
{
	var Y;
	var T,C;
	
	 if(yui3)
	 {
	    Y = yui3;
    }
    T = Y.JSON.parse(terciary);
    C = Y.JSON.parse(cuaternary);

	terciarySelect = T;
	cuaternarySelect = C;
	/*console.log(T);*/
	/*console.log(C);*/
}

function FiltraLibros(step){

currentUrl = 'modedit.php?add=smartclassroom&type=&course=2&section=1&return=0&sr=0&step='+step;
valuePrimary = document.getElementById('id_scrprimary').value;
valueSecondary = document.getElementById('id_scrsecondary').value; 
document.getElementById('mform1').setAttribute('action', currentUrl+'&amp;scrprimary='+valuePrimary+'&amp;scrsecondary='+valueSecondary);
document.getElementById('mform1').submit();
}

function CreaActivity(step){

url = document.getElementById('id_scrcuaternary').value;
name = document.getElementById('id_scrcuaternary').options[document.getElementById('id_scrcuaternary').selectedIndex].text;
course = document.getElementsByName('course')[0].value;
currentUrl = 'modedit.php?add=lti&type=&course='+course+'&section=1&return=0&sr=0&step='+step+'&unitName='+name+'&url='+url;
console.log(currentUrl);
valueCuaternary = document.getElementById('id_scrcuaternary').value; 
document.getElementById('mform1').setAttribute('action', currentUrl);
document.getElementById('mform1').submit();

}

function initFakeHidden()
{
	document.getElementsByName("modulename")[0].value = 'lti';
	document.getElementsByName("add")[0].value = 'lti';
	document.getElementsByName("_qf__mod_smartclassroom_mod_form")[0].setAttribute('name',"_qf__mod_lti_mod_form");
										 
}