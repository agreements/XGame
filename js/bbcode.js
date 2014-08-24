function AddText(addtext) {
	var current = document.post.content.value;
	var newtext = current + addtext;
	document.post.content.value = newtext;
	document.post.content.focus();
}

// button [B]
function AddB() {
	inserttext = prompt("\n[B]xxx[/B]");
	if ((inserttext != null)) addtext = " [B]" + inserttext + "[/B] ";
	else addtext="";
	
	AddText(addtext);
}

// button [U] 
function AddU() {
	inserttext = prompt("\n[U]xxx[/U]");
	if ((inserttext != null)) addtext = " [U]" + inserttext + "[/U] ";
	else addtext="";
	
	AddText(addtext);
}

// button [I] 
function AddI() {
	inserttext = prompt("\n[I]xxx[/I]");
	if ((inserttext != null)) addtext = " [I]" + inserttext + "[/I] ";
	else addtext="";
	
	AddText(addtext);
}

// button [URL] and [EMAIL] 
function AddLink(thetype) {
	linktext = prompt("Enter a Linkname (optional)", "");
	var prompttext;
	if (thetype == "URL") linkurl = prompt("Enter the URL", "http://");
	else linkurl = prompt("Enter the E-Mail Address", "");
	
	if ((linkurl != "http://") && (linkurl != "") && (linkurl != null)) {
		if ((linktext != null) && (linktext != "")) {
			addtext = " [" + thetype + "=" + linkurl + "]" + linktext + "[/" + thetype + "] ";
		}
		else {
			addtext = " [" + thetype + "]" + linkurl + "[/" + thetype + "] ";
		}
	}
	else addtext = "";
	
	AddText(addtext);
}

// button [IMG]
function AddImg() {
	inserttext = prompt("Enter the URL to the Image:" + "\n[IMG]xxx[/IMG]", "http://");
	if ((inserttext != "http://") && (inserttext != "") && (inserttext != null)) addtext = " [IMG]" + inserttext + "[/IMG] ";
	else addtext="";
	
	AddText(addtext);
}


// button [LIST]
function AddList() {
	type = prompt("enter '1' for a numbered List, 'a' for an alphabetic List or '' for a pointed List", "");
	if ((type == "a") || (type == "1")) {
		list = " [LIST=" + type + "]\n";
		listend = "[/LIST=" + type + "] ";
	}
	else {
		list = " [LIST]\n";
		listend = "[/LIST] ";
	}
	entry = "start";
	while ((entry != "") && (entry != null)) {
		entry = prompt("Enter a List-Point. Enter nothing or click 'Cancel' to finish the list.", "");
		if ((entry != "") && (entry != null))
			list = list + "[*]" + entry + "[/*]\n";
	}
	addtext = list + listend;
	
	AddText(addtext);
}

// button [quote] 
function AddQuote() {
	inserttext = prompt("\n[QUOTE]xxx[/QUOTE]");
	if ((inserttext != null)) addtext = " [QUOTE]" + inserttext + "[/QUOTE] ";
	else addtext="";
	
	AddText(addtext);
}

// button [Toggle]
function AddPlayer() {
	inserttext = prompt("Enter player ID:" + "\n[PLAYER=xxx][/PLAYER]");
	inserttext1 = prompt("Enter player name:");
	if ((inserttext != "") && (inserttext1 != "") && (inserttext != null) && (inserttext1 != null)) addtext = " [PLAYER="+inserttext+"]" + inserttext1 + "[/PLAYER] ";
	else addtext="[PLAYER=xx]xx[/PLAYER]";
	
	AddText(addtext);
}