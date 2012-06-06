var visibleBox = "register";

function showBox(id)
{
	/*document.getElementById(visibleBox).setAttribute("class", "box fadeOut");
	document.getElementById(id).setAttribute("class", "box fadeIn");*/
	
	/*window.setTimeout("unvisible("+visibleBox+")", 501);
	window.setTimeout("visible("+id+")", 501);*/
	
	document.getElementById(visibleBox).setAttribute("class", "box unvisible");
	document.getElementById(id).setAttribute("class", "box");
	
	visibleBox = id;
}

/*function unvisible(id)
{
	
	id.setAttribute("class", "box unvisible");
}

function visible(id)
{
	id.setAttribute("class", "box");
}*/