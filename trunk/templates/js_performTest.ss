
var w = window.open(baseHref() + "$getcontrollerurl/perform/$ID/" , "performtest");
if (!w) {
	alert('Please allow popup for this site.');
}
else {
	w.focus();
}
