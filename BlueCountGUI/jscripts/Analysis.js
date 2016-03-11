/**
 * @file Analysis.js
 * @author Fabien.Pelisson@blueeyevideo.com
 */

/*
 * Store Intervals id for each
 * Indicator so we could stop them
 * when the Indicator changes.
 */
var IndicatorIntervals = new Array();

function getParamsId(id)
{
  return "params_" + id;
}

function getMainPartId(id)
{
  return "MainPart_" + id;
}

function getDataContentsId(id)
{
  return "DataContents_" + id;
}

function getIdFromMainPartId(mainpartId)
{
  return mainpartId.split("_")[1];
}

function createIndicatorInterval(id)
{
    clearIndicatorInterval(id);
    IndicatorIntervals[id] = setInterval("updateIndicatorData(" + id + ")", 300000);
}

function clearIndicatorInterval(id)
{
    if(IndicatorIntervals[id])
	{
	    clearInterval(IndicatorIntervals[id]);
	    IndicatorIntervals[id] = false;
	}
}

function getIndicatorURLParams(id, toConcat)
{
  var elt = document.getElementById(getParamsId(id));
  var childs = elt.childNodes;
  var url = "";
  for(var i = 0; i < childs.length; i++)
    {
      if(childs[i].nodeName == "INPUT")
	{
	  if(!toConcat)
	    {
       	      url += "?";
	      toConcat = true;
	    }
	  else
	    {
	      url += "&";
	    }
	  url += childs[i].name + '=' + childs[i].value;
	}
    }  
  return url;
}

function updateIndicatorContents(id, HTMLHttp)
{
  var root = HTMLHttp.responseText;
  var elt = document.getElementById(id);
  elt.innerHTML = root;
}

function updateIndicator(id)
{
  clearIndicatorInterval(id);
  var elt = document.getElementById(getParamsId(id));
  var childs = elt.childNodes;
  var url = "Indicators.php" + getIndicatorURLParams(id, false);
  BlueCountLang(url, getMainPartId(id), 
		function(id2, HTMLHttp) 
                  { 
		      updateIndicatorContents(id2, HTMLHttp); 
		      InitCalendar(id); 
		      createIndicatorInterval(id);
		  } );
}

function updateIndicatorData(id)
{
  var elt = document.getElementById(getParamsId(id));
  var childs = elt.childNodes;
  var url = "IndicatorData.php" + getIndicatorURLParams(id, false);
  BlueCountLang(url, getDataContentsId(id), updateIndicatorContents);
}

function changeIndicatorParameter(id, name, value)
{
  var elt = document.getElementById(getParamsId(id));
  var childs = elt.childNodes;
  for(var i = 0; i < childs.length; i++)
    {
      if(childs[i].nodeName == "INPUT" && childs[i].name == name)
	{
	  childs[i].value = value;
	  break;
	}
    }
}

function getNextNodeIndicator(n)
{
  var n = n.nextSibling;
  while(n)
    {
      if(n.nodeType == 1 &&
	 n.getAttribute("class") == "MainPart")
	{
	  return n;
	}
      n = n.nextSibling;
    }
  return n;
}

function getPrevNodeIndicator(n)
{
  var n = n.prevSibling;
  while(n)
    {
      if(n.nodeType == 1 &&
	 n.getAttribute("class") == "MainPart")
	{
	  return n;
	}
      n = n.prevSibling;
    }
  return n;
}

function moveIndicatorDown(id)
{
  var node1 = document.getElementById(getMainPartId(id));
  if(node1)
    {
      // find next node just after
      var node2 = getNextNodeIndicator(node1);

      if(node2)
	{
	  node1.parentNode.replaceChild(node2.cloneNode(true), node1);
	  node2.parentNode.replaceChild(node1.cloneNode(true), node2);

	  InitCalendar(id);
	  // retrieve the Indicator id of the swapped node
	  // should be contained in the div id attribute in the form
	  // MainPart_id
	  InitCalendar(getIdFromMainPartId(node2.getAttribute("id")));
	}
    }
}

// fab : seems like moveIndicatorDown must use func cb
function moveIndicatorUp(id)
{
  var node1 = document.getElementById(getMainPartId(id));
  if(node1)
    {
      // find prev node just before
      var node2 = getPrevNodeIndicator(node1);

      if(node2)
	{
	  node1.parentNode.replaceChild(node2.cloneNode(true), node1);
	  node2.parentNode.replaceChild(node1.cloneNode(true), node2);

	  InitCalendar(id);
	  // retrieve the Indicator id of the swapped node
	  // should be contained in the div id attribute in the form
	  // MainPart_id
	  InitCalendar(getIdFromMainPartId(node2.getAttribute("id")));
	}
    }
}

function stepChanged(id, value)
{
  changeIndicatorParameter(id, "Step", value);
  updateIndicatorData(id);
}

function formatChanged(id, value)
{
  changeIndicatorParameter(id, "Format", value);
  updateIndicatorData(id);
}

function idChanged(id, value)
{
  changeIndicatorParameter(id, "id", value);
  updateIndicatorData(id);
}

function hourChanged(id, value)
{
  changeIndicatorParameter(id, 'HourValue', value);
  updateIndicatorData(id);
}

function analysisChanged(id, value) 
{
  changeIndicatorParameter(id, 'Analysis', value);
  updateIndicator(id);
};

function entityChanged(id, value)
{
  changeIndicatorParameter(id, 'Entity', value);
  var url = "/BlueCountGUI/BlueCountGUILang.php?function=getCountersIds" + getIndicatorURLParams(id, true);
  BlueCountLang(url,  
	        id, 
		function(id, xmlHttp) 
		{ 
		   BlueCountLang_ChangeSelect(id + '_id', xmlHttp); 
		   var elt = document.getElementsByName(id + '_id')[0];
		   changeIndicatorParameter(id, 'id', elt.options[elt.selectedIndex].value); 
		   updateIndicatorData(id); 
		});
}
