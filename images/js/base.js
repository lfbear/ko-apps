(function(){
	$('body').delegate('#logoutlink', 'click', function(){
		$('#logoutform').submit();
	});
})();
