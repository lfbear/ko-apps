(function(){
	$('body').delegate('#logoutlink', 'click', function(){
		$.post('/rest/user/login/' + $('#globaldata').data('uid'), {'method':'DELETE'}, function(data, status){
			if (data.errno) {
				alert(data.error);
			} else {
				window.location.reload();
			}
		}, 'json');
	});
})();
