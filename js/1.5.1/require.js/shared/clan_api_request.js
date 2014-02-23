define(function(){
	var ApiRequest = Class.extend({
		
		init: function(path,success,fail) {	
			var url = 'http://clanapi.wotcs.com/'+path.join('/');
			
			
			$.ajax({
				url: url,
				dataType: 'json',
				success: success,
				error: function(jqXHR, textStatus, errorThrown) {
					console.log('AJAX Error: '+url);
					if(fail){
						fail();
					}
				}
			});
			
	    }
		
	});
	
	return ApiRequest;
});