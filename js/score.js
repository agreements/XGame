// show and hide sections of a form
function preparePage() {
	document.getElementById("status").onclick = function() {
		if (document.getElementById("status").value == "played") {
		// use CSS style to show it
		document.getElementById("result").style.display = "block";
		} else {
			// hide the div
			document.getElementById("result").style.display = "none";
		}
	};
	// now hide it on the initial page load.
	if (document.getElementById("status").value == "played") {
		document.getElementById("result").style.display = "block";
	} else {
		// hide the div
		document.getElementById("result").style.display = "none";
	};
}

window.onload =  function() {
	preparePage();
};
