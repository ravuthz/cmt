function ShowHideProcess(Content) {
	this.Desc = Content
	
	this.Show = ShowMe
	this.Hide = HideMe
}

function ShowMe() {
	
	if (document.layers)
		document.write('<layer name=wait>' + this.Desc + '</layer>')
	else
		document.write('<div id=wait>' + this.Desc + '</div>')		
}

function HideMe() {
	var current
	
	if (document.all) {
		current = document.all.wait
	} else if (document.getElementById) {
		current = document.getElementById("wait")
	} else if (document.layers) {
		current = document.layers['wait']
		current.visibility = "hide"
		return
	}
	current.style.display = "none"	
}