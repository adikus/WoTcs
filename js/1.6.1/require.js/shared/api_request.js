define(function(){
	var ApiRequest = Class.extend({
		
		init: function(server,path,wid,data,success,fail) {
			
			this.baseUrl = URL_BASE;
			
			var thisG = this;

			$.ajax({
				url: 'http://wotcsapi'+server+'.herokuapp.com/'+path+'/'+wid,
				data: data,
				dataType: 'json',
				success: success,
				error: function(jqXHR, textStatus, errorThrown) {
					console.log('AJAX Error: '+thisG.baseUrl+path);
					fail();
				}
			});	
	    }
	});
	
	return ApiRequest;
});