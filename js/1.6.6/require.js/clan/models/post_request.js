define(function(){
	var PostRequest = Class.extend({
		
		init: function(path,data) {
			
			this.baseUrl = URL_BASE;
			
			var thisG = this;
			
			$.ajax({
				url: thisG.baseUrl+path,
				data: data,
				type: 'POST', 
				dataType: 'json',
			});
			
	    },
		
	});
	
	return PostRequest;
});