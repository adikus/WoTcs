define(['./../../shared/site_request','./../../shared/api_request','models/player'],function(SiteRequest,ApiRequest,Player){
	var Clan = Class.extend({
		
		init: function(wid) {
			this.wid = wid;
			this.members = [];
			this.memberIds = [];
			this.tag = '';
			this.name = '';
			this.motto = '';
			this.description = '';
			
			this.status = 0;
			this.retry = 0;
			this.wait = 0;
			
			this.queue = 0;
			this.queueMax = 0;
			this.secondBar = false;
	    },
	    
	    loadInfo: function(){
	    	console.log('Loading clan info...');
			$('#alert_msg').html('Loading clan info...');
	    	this.status = 0.5;
	    	
	    	var self = this,
	    		src = WOT_BASE+"media/clans/emblems/cl_"+WID.toString().slice(-3)+"/"+WID+"/emblem_64x64.png";
	    	
          $.get('http://clanapi-wotcs-eu.herokuapp.com/clans/'+this.wid);
	    	  request = new ApiRequest('clans','clan',this.wid,{},function(data){
	    		if(parseInt(data.clan_status,10) !== 1){
					self.status = 4;
					$('#alert_msg').removeClass().addClass('alert alert-error').html('Clan does not exist.<a class="close" data-dismiss="alert" href="#">&times;</a>').alert();
    				new SiteRequest('save_clan.php','post',{
    					wid: self.wid,
						remove: true
					},function(){console.log('Clan saved.');});
    				return false;
				}
	    		if(data.tag == undefined){
	    			if(data.clan_status !== 0 && data.clan_status !== "0"){
						self.status = 3;
						$('.hero-unit h2').html(data.clan_status);
	    				return false;
					}
	    			self.status = 0;
	    			self.wait = 2500;
	    			console.log("Waiting...");
					self.retry++;
	    			return false;
	    		}
	    		if(data.tag != ""){
	    			self.tag = data.tag;
		    		$('.hero-unit h1 .tag').html("["+self.tag+"] ");
		    		self.name = data.name;
					$('.hero-unit h2').html(self.name);
					document.title = 'WoT cs | '+data.tag;
		    		self.motto = data.motto;
					$('.hero-unit #motto').html(self.motto);
		    		self.description = data.description.replace(/"(.*?)":(.*?)(\s|<\/p>)/g,'<a href="$2">$1</a>$3');
		    		$('.hero-unit #description').html(self.description);
		    		self.description = data.description;
					$('.hero-unit #members_count').html(data.members.length);
					$('.hero-unit #last_updated').html("Updated "+self.formatTime((new Date(data.updated_at)).getTime()));
	    		}else{
	    			self.WGError = true;
	    			console.log('WG Server error');
	    		}
				self.memberIds = data.members;
				self.status = 1;
				self.retry = 0;
	    	},function(){
    			$('.hero-unit h1 img').attr('src',src);
    			$('#alert_msg').removeClass().addClass('alert alert-error').html('Unable to load clan info. Try again later.<a class="close" data-dismiss="alert" href="#">&times;</a>').alert();
	    	});
	    },
	    
	    loadPlayers: function(force,retry){
	    	console.log('Loading players...');
			$('#alert_msg').html('Loading players...');
			
			this.status = 1.5;
			
			var self = this,
				src = WOT_BASE+"media/clans/emblems/clans_"+WID.toString().charAt(0)+"/"+WID+"/emblem_64x64.png";
			
			request = new ApiRequest('players','clans',this.wid,{
				last: this.last || 0,
				retry: retry || 0,
				force: force || 0
			},function(res){
				if(res.status != 'ok')return;
				var data = res.data;

	    		if(!self.secondBar && data.last_pos > self.memberIds.length){
	    			self.secondBar = true;
	    			self.queueMax = data.last_pos - self.memberIds.length;
	    			self.queue = data.last_pos - self.memberIds.length;
	    		}else if(data.last_pos > self.memberIds.length){
	    			var newq = data.last_pos - self.memberIds.length;
	    			if(newq != self.queue)added++;
	    			self.queue = newq;
	    		}
	    		
	    		if(!self.last || self.last < data.last)self.last = data.last;
	    		var added = 0;
				for(var i in data.members){
					var pos = $.inArray(data.members[i].wid,self.memberIds);
					if(pos > -1){
						delete self.memberIds[pos];
						added++;
						var tempPlayer = new Player(data.members[i].wid,data.members[i].name,data.members[i].updated_at);
						tempPlayer.data = {best:data.members[i].vehs,stats:data.members[i].stats_current};
						tempPlayer.fromData();
						self.members.push(tempPlayer);
					}
				}
				if(added == 0)self.retry++;
	    		if(data.is_done){
	    			self.retry = 0;
	    			self.status = 2;
	    			if(data.total){
	    				self.total = data.total;
	    			}
	    		}else{
	    			self.status = 1;
	    		}
	    	},function(){
    			$('#alert_msg').removeClass().addClass('alert alert-error').html('Unable to load players. Try again later.<a class="close" data-dismiss="alert" href="#">&times;</a>').alert();
	    	});
	    },
	    
	    notUpdated: function() {
	    	var ret = 0;
	    	for(var i in this.memberIds){
	    		if(this.memberIds[i] != undefined){
	    			ret++;
	    		}
	    	}
	    	return ret;
	    },

	    preUpdate: function() {
	    	return this.members[0] && this.members[0].stats.WN8 === undefined
	    },
	    
	    membersLoaded: function(){
	    	for(var i in this.members){
	    		if(!this.members[i].loaded){
	    			return false;
	    		}
	    	}
	    	return true;
	    },
	    
	    membersLoadedCount: function(){
	    	u = 0;
	    	for(var i in this.members){
	    		if(this.members[i].loaded){
	    			u++;
	    		}
	    	}
	    	return u;
	    },
	    
	    addNotUpdatedPlayers: function() {
	    	for(var i in this.memberIds){
	    		if(this.memberIds[i] != undefined){
					var tempPlayer = new Player(this.memberIds[i],"",0);
					this.members.push(tempPlayer);
	    			delete this.memberIds[i];
	    		}
	    	}
	    },
	    
	    updateLoadBar: function(){
	    	var done = Math.min(this.memberIds.length - this.notUpdated(),this.membersLoadedCount()),
	    		percent = Math.round(done/this.memberIds.length*100*100)/100;
	    	
	    	if(this.secondBar){
	    		var qpercent = Math.min(Math.round((this.queueMax - this.queue)/(this.queueMax-5) *50*100)/100,50),
	    			npercent = Math.round(percent/2);
	    		if(percent == 100)npercent = 100 - qpercent;
	    		$('#queue_bar').css('width',qpercent+'%');
	    		$('#load_bar').css('width',npercent+'%');
	    	}else{
		    	$('#load_bar').css('width',percent+'%');
		    }
	    	if(percent == 100)$('.progress').removeClass('active');
	    },
	    
	    roundNumber: function(num, dec) {
			var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
			return result;
		},
	    
	    showStats: function(total){
	    	var WR = Math.round(total.stats_current.WIN/total.stats_current.GPL*10000)/100,
	    		SC3 = total.stats_current.SC3,
	    		SC3A = Math.round(total.stats_current.SC3/total.stats_current.member_count*100)/100,
	    		WN7 = total.stats_current.WN7,
	    		WN7A = Math.round(total.stats_current.WN7/total.stats_current.member_count*100)/100,
	    		EFR = total.stats_current.EFR,
	    		EFRA = Math.round(total.stats_current.EFR/total.stats_current.member_count*100)/100;
	    	
	    	$('#winrate_average').html(WR+"%").addClass("label-c"+labelClass(WR,"CWIN"));
	    	
	    	$('#efficiency_total').html(formatNumber(EFR)).addClass("label-c"+labelClass(EFR,"CEFR"));
	    	$('#efficiency_average').html(EFRA).addClass("label-c"+labelClass(EFRA,"CEFRA"));
	    	
	    	$('#wn7_total').html(formatNumber(WN7)).addClass("label-c"+labelClass(WN7,"CWN7"));
	    	$('#wn7_average').html(WN7A).addClass("label-c"+labelClass(WN7A,"CWN7A"));
	    	
	    	$('#score').html(formatNumber(SC3)).addClass("label-c"+labelClass(SC3,"CSC3"));
	    	$('#score_average').html(SC3A).addClass("label-c"+labelClass(SC3A,"CSC3A"));
	    	
	    	if(!this.WGError){
				new SiteRequest('save_clan.php','post',{
					wid: this.wid,
					tag: this.tag,
					name: this.name,
					description: this.description,
					motto: this.motto,
					WR: WR,
					SC3: SC3,
					EFR: EFR,
					WN7: WN7
				},function(){console.log('Clan saved.');});
			}
	    },
	    
	    countTops: function(){
	    	this.total.tops = [0,0,0,0,0];
	    	for(var i in this.members){
	    		for(var j in this.members[i].top){
	    			this.total.tops[j] += this.members[i].top[j];
	    		}
	    	}
	    },
		
		formatTime: function(time){
			now = (new Date()).getTime();
			if(now-time < 3600*1000){
				diff = Math.round((now-time)/60000);
				if(diff == 1)text = ' minute ago'; else text = ' minutes ago';
				return diff+text;
			}
			else if(now-time < 3600*24*1000){
				diff = Math.round((now-time)/3600000);
				if(diff == 1)text = ' hour ago'; else text = ' hours ago';
				return diff+text;
			}
			else {
				diff = Math.round((now-time)/3600/24/1000);
				if(diff == 1)text = ' day ago'; else text = ' days ago';
				return diff+text;
			}
		},
		
	});
	
	return Clan;
});
