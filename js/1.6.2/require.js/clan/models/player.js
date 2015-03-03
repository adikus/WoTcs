define(['./../../shared/site_request','./../../shared/api_request','models/renderer'],function(SiteRequest,ApiRequest,Renderer){
	var Player = Class.extend({
		
		init: function(wid,name,updated_at) {
			this.wid = wid;
			this.name = name;
			this.updated_at = new Date(updated_at);
			this.loaded = false;
			this.waiting = false;
			this.battles = [0,0,0,0,0];
			this.wins = [0,0,0,0,0];
			
			this.status = 0;
			this.retry = 0;
	    },
	    
	    updated: function() {
	    	var now = new Date();
	    	return(now.getTime() - 1000*60*60*12 < this.updated_at.getTime());
	    },
	    
	    loadData: function() {
	    	//console.log('Loading player data ('+this.wid+')...');
			this.waiting = true;
	    	
	    	var self = this;
	    	
	    	request = new ApiRequest('players','player',this.wid,{},function(data){
	    		if(data.status != "ok"){
	    			self.status = 0;
		    		self.retry++;
		    		self.waiting = false;
		    		return false;
	    		}
	    		self.tanks = data.vehs;
	    		self.name = data.name;
	    		self.stats = data.stats_current;
	    		self.updated_at = new Date(data.updated_at);
	    		self.countBattles();
	    		self.loaded = true;
				self.retry = 0;
				self.waiting = false;
				self.renderer = new Renderer(self);
	    	},function(){
	    		self.status = 0;
	    		self.retry++;
	    		self.waiting = false;
	    	});
	    },
	    
	    fromData: function() {
	    	//console.log('Loading player data ('+this.name+')...');
	    	
	    	this.tanks = this.data.best;
	    	this.stats = this.data.stats;
	    	this.updatedBefore = this.data.updated;
	    	this.countBattles();
	    	this.loaded = true;
	    	this.getTop();
	    	this.renderer = new Renderer(this);
	    },
	    
	    getTop: function() {
	    	this.top = [];
	    	for(var i = 1;i <= 4;i++){
	    		if(!this.tanks[i])this.top[i] = 0;
	    		else{
	    			this.top[i] = this.tanks[i].tier == 10 ? 1 : 0;
	    		}
	    	}
	    },
	    
	    countBattles: function() {
	    	for(var i in this.tanks){
	    		for(var j in this.tanks[i]['tanks']){
	    			this.battles[i] += parseInt(this.tanks[i]['tanks'][j].battles);
	    			this.wins[i] += parseInt(this.tanks[i]['tanks'][j].wins);
	    		}
	    	}
	    }
		
	});
	
	return Player;
});