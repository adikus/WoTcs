define(function(){
	var SiteRequest = Class.extend({
		
		init: function(path,type,data,success,fail) {
			
			$.ajax({
				url: URL_BASE+path,
				dataType: 'json',
				type: type,
				data: data,
				success: success,
				error: function(jqXHR, textStatus, errorThrown) {
					console.log('AJAX Error: '+URL_BASE+path);
					if(fail)fail();
				}
			});
			
	    },
		
	});
	
	return SiteRequest;
});