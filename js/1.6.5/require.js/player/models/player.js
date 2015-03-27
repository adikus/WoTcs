define(['./../../shared/api_request','./../../shared/clan_api_request','./../../shared/site_request','./../../shared/renderer','./../../shared/stats'],function(ApiRequest,ClanApiRequest,SiteRequest,Renderer,Stats){
	var Player = Class.extend({
		
		init: function(wid) {
			this.wid = wid;
			this.r = new Renderer(this);
			
			this.percentile = false;
			this.changes = false;
	    		
	    	this.renderChanges();
	    },
	    
	    loadFromAPI: function() {
	    	var self = this;
	    	
	    	new ApiRequest('players','players',this.wid,{},function(res){
	    		var data = res.data;

	    		if(data.name){
		    		document.title = 'WoT cs | '+data.name;
		    		self.r.render({".hero-unit .name":data.name});	    			
	    		}

	    		if(data.status < 0){
					self.r.render({
						'#last_updated': "Updated "+formatTime((new Date(data.updated_at)).getTime())
					});
					$('.hero-unit h1 img').remove();

	    			$('#alert_msg').removeClass().addClass('alert alert-error').html(
						'It looks like this account does not exist.'
					).alert();
					return;
	    		}
	    		
	    		if(data.clan_id && data.clan_id != "0"){
	    			self.loadClanName(data.clan_id);
	    			$('.hero-unit h1 img').attr('src',self.getClanImgSrc(data.clan_id));
	    		}else{
	    			$('.hero-unit h1 img').remove();
	    		}
	    		
	    		var sm = new Stats('players',self.wid);
	    		
	    		self.stats = sm.calcStats(data.stats_current);

	    		if(data.logout_at)
    				self.r.render({'#last_logout': "Last time in game "+formatTime((new Date(data.logout_at)).getTime())});
	    		self.renderHeroUnit();
	    		
	    		sm.renderStatsTable();
	    		sm.renderPercentiles();
	    		sm.renderStatsHistory();
	    		
	    		for(var i = 1;i < 5;i++)self.r.renderTanks(data.vehs[i]?data.vehs[i].tanks:[],i);
	    	});
	    },
	    
	    renderHeroUnit: function() {
	    	this.r.render({
    			'#battles': {v:this.stats.GPL,c:'GPL'},
    			'#frags': {v:this.stats.FRGA,c:'FRG'},
    			'#damage': {v:this.stats.DMGA,c:'DMG'},
    			'#winrate': {v:this.stats.WINA,c:'WIN',p:true},
    			'#efficiency': {v:this.stats.EFR,c:'EFR'},
    			'#score': {v:this.stats.SC3,c:'SC3'},
    			'#wn7': {v:this.stats.WN7,c:'WN7'},
    			'#wn8': {v:this.stats.WN8,c:'WN8'},
    			'#last_updated': "Updated "+formatTime((new Date(this.stats.updated_at)).getTime())
    		});
	    },
	    
	    renderChanges: function() {
	    	var self = this;
	    	
	    	$('a[href="#player-history"]').on('show',function(){
				if(!self.changes){
					new ClanApiRequest(['players',self.wid,'changes'],function(data){
			    		for(var i in data.changes){
							var date = new Date(data.changes[i].changed_at);	
							row = '<tr><td><a href="'+URL_BASE+'clan.php?wid='+data.changes[i].clan_id+'">['+data.changes[i].clan_tag+']'+data.changes[i].clan_name+'</a></td>';
							change = data.changes[i].joined?'Joined':'Left';
							row += '<td>'+change+'</td>';
							row += '<td>'+date.getDate()+'.'+(date.getMonth()+1)+'.'+date.getFullYear()+'</td></tr>';
							$('#player_changes_table tbody').append(row);
						}
						$('#player-history .detail-loading').hide();
						self.changes = true;
			    	});
				}
			});
	    },
	    
	    loadClanName: function(wid) {
	    	var self = this;
	    	
	    	new ApiRequest('clans','clan',wid,{},function(data){
	    		self.r.render({
	    			".hero-unit h1 .tag": '<a href="'+URL_BASE+'clan.php?wid='+wid+'">['+data.tag+']</a> ',
	    			'#alert_msg': 'Loading done.<a class="close" data-dismiss="alert" href="#">&times;</a>'
	    		});
				$('#alert_msg').alert();
	    	});
	    },
	    
	    getClanImgSrc: function(wid){
	    	return WOT_BASE+"media/clans/emblems/cl_"+wid.toString().slice(-3)+"/"+wid+"/emblem_64x64.png";
	    },
		
	});
	
	return Player;
});