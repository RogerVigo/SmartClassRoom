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

			option.value = i;

			document.getElementById('id_scrterciary').appendChild(option);
		}
		document.getElementById('id_scrterciary').removeAttribute('disabled');	
	} 


}

function fillCuaternary()
{
	var valueTerciary = document.getElementById('id_scrterciary').value;

	var possibleValues = cuaternarySelect[valueTerciary]; 

	document.getElementById('id_scrcuaternary').innerHTML = document.getElementById('id_scrcuaternary').firstElementChild.outerHTML;
	var keys = Object.keys(possibleValues);
		for (i=0 ; i < keys.length ; i++)
		{
			var key = keys[i];
			var option = document.createElement("option");
			option.text = possibleValues[key].title;

			option.value = possibleValues[key].url;

			document.getElementById('id_scrcuaternary').appendChild(option);
		}


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

}

function FiltraLibros(step){

valuePrimary = document.getElementById('id_scrprimary').value;
valueSecondary = document.getElementById('id_scrsecondary').value;

if ((valuePrimary != '') && (valueSecondary != '')){

course = document.getElementsByName('course')[0].value;
section = document.getElementsByName('section')[0].value;
nativemode = document.getElementById('id_nativemode').value;
currentUrl = 'modedit.php?add=smartclassroom&course='+course+'&section='+section+'&return=0&sr=0&step='+step;
window.location.href = currentUrl+'&scrprimary='+valuePrimary+'&scrsecondary='+valueSecondary+'&nativemode='+nativemode;
}
else alert('Seleccione primero las opciones en los dos filtros');
 

}

function CreaActivity(step){

url = document.getElementById('id_scrcuaternary').value;

if (url != '')
{
	
	name = document.getElementById('id_scrcuaternary').options[document.getElementById('id_scrcuaternary').selectedIndex].text;
	
	section = document.getElementsByName('section')[0].value;
	course = document.getElementsByName('course')[0].value;
	nativemode = document.getElementsByName('nativemode')[0].value;
	
	currentUrl = 'modedit.php?add=lti&type=&course='+course+'&section='+section+'&return=0&sr=0&step='+step+'&unitName='+name+'&url='+url+'&nativemode='+nativemode;
	console.log(currentUrl);
	valueCuaternary = document.getElementById('id_scrcuaternary').value; 
	document.getElementById('mform1').setAttribute('action', currentUrl);
	document.getElementById('mform1').submit();
}
else alert('Seleccione primero unidad.');
}

function initFakeHidden()
{
	document.getElementsByName("modulename")[0].value = 'lti';
	document.getElementsByName("add")[0].value = 'lti';
	document.getElementsByName("_qf__mod_smartclassroom_mod_form")[0].setAttribute('name',"_qf__mod_lti_mod_form");
										 
}