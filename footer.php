<div class="footer">Powered by <a href="http://www.mobileslife.com">batmaster</a>.</div>
<!-- Le javascript
    ================================================== --> 
<!-- Placed at the end of the document so the pages load faster --> 
<script src="js/jquery.js"></script> 
<script src="js/bootstrap-transition.js"></script> 
<script src="js/bootstrap-alert.js"></script> 
<script src="js/bootstrap-modal.js"></script> 
<script src="js/bootstrap-dropdown.js"></script> 
<script src="js/bootstrap-scrollspy.js"></script> 
<script src="js/bootstrap-tab.js"></script> 
<script src="js/bootstrap-tooltip.js"></script> 
<script src="js/bootstrap-popover.js"></script> 
<script src="js/bootstrap-button.js"></script> 
<script src="js/bootstrap-collapse.js"></script> 
<script src="js/bootstrap-carousel.js"></script> 
<script src="js/bootstrap-typeahead.js"></script> 
<script>
	function show() {
		var digital = new Date();
		var hours = digital.getHours();
		var minutes = digital.getMinutes();
		var seconds = digital.getSeconds();
		var dn = "AM"; 
		
		if (hours > 12) {
			dn = "PM";
			hours = hours - 12;
		}
		if (hours == 0)
			hours = 12;
		if (minutes <= 9)
			minutes = "0" + minutes;
		if (seconds <= 9)
			seconds = "0" + seconds;
		
		document.getElementById('Clock').innerHTML = hours + ":" + minutes + ":" + seconds + " " + dn;
		setTimeout("show()", 1000);
	}
	
	show();
</script>
</body></html>