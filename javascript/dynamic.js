function showLoadingIcon() 
	{
		var icon = elt("loadingImage");
		if (icon) icon.style.visibility = "visible";
	}
	
	function toggleHidden(hiddenEl)
	{
		var el = elt(hiddenEl);	
		if( !el ) return;
		
		if(el.style.visibility != "visible")
			showHidden(el);
		else
			hideElem(el);
	}
	
	function showHidden(el)
	{
		el.style.visibility = "visible";
	}
	
	function hideElem(el)
	{
		el.style.visibility = "hidden";		
	}


	 	 
	 // The following are necessary for IE7.  When the user pastes a URL into the
	 // location bar, the old definition and values are carried over.  It's no longer
	 // possible to check that one of these vars is undefined.  Define 'em here and check
	 // their value.
	window.onLoad_called = false;
	window.already_loaded = false;

	 function onLoad() {
		if ( typeof window.onLoad_called != "undefined" && window.onLoad_called == true ) {
			return;
		}
		
				if( screen.width < 1024 || screen.height < 768 ) {
			clearLaunchPreviouslyFailedCookie();
			window.location.href = "system_requirements?resolution=unsupported";
		}
				 
		window.onLoad_called = true;
		callJScriptOnLoadHandler();
	 }