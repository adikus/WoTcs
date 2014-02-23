define(function(){
	var ApiRequest = Class.extend({
		
		init: function(server,path,wid,data,success,fail) {
			
			this.baseUrl = URL_BASE;
			
			var thisG = this,
				dataString = this.dataString(wid,data);
			
			$.ajax({
				url: 'http://wotcsapi'+server+'.herokuapp.com/'+path+'/'+dataString,
				dataType: 'json',
				success: success,
				error: function(jqXHR, textStatus, errorThrown) {
					console.log('AJAX Error: '+thisG.baseUrl+path);
					fail();
				}
			});
			
	    },
	    
	    dataString: function(wid,data){
	    	var parts = [];
	    	for(var i in data){
	    		if(data[i])parts.push(i+"="+data[i]);
	    	}
	    	parts.unshift(wid);
	    	return parts.join('/');
	    }
		
	});
	
	return ApiRequest;
});