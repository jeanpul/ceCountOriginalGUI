function updateNTPDate(id, timer, params)
{
    getNTPDate(id, params);
    setTimeout("updateNTPDate(\"" + id + "\"," + timer + ",\"" + params + "\")", timer);
}

function getNTPDate(id, params)
{
    var url = "gettime.php?" + params;
    BlueCountLang(url, id,
		  function(id, HTMLHttp)
		  {
		      var elt = document.getElementById(id);
		      elt.innerHTML = HTMLHttp.responseText;
		  });
}
