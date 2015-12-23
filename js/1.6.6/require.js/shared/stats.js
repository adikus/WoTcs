define(['./api_request','./clan_api_request','./site_request','./renderer'],function(ApiRequest,ClanApiRequest,SiteRequest,Renderer){
	var Stats = Class.extend({
		
		monthNames:[ "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December" ],
		
		init: function(type,wid) {
			this.wid = wid;
			this.type = type;
			this.r = new Renderer(this);
			this.changesLoaded = false;
			this.changes = {};
			this.historyRowData = [];
			this.historyIdMap = {};
	    },
	    
	    labelClass: function(key) {
	    	var addClass = ['GPL','KD','EFR','WN7','WN8','SC3','WINA','SURA','FRGA','SPTA','DMGA','CPTA','DPTA','EXPA','WN7A','WN8A','EFRA','SC3A',],
	    		clanClassesSpec = ['EFRA','WN7A','WN8A','SC3A'];
	    	if(key == 'member_count')return "CMC";
	    	if(addClass.indexOf(key) == -1)return false;
	    	if(this.type == "players")var c = key.length > 3?key.substring(0,3):key;
	    	else {
	    		var c = "C";
	    		if(clanClassesSpec.indexOf(key) == -1)c += key.length > 3?key.substring(0,3):key;
	    		else c += key;
	    	}
	    	return c;
	    },
	    
	    renderStatsTable: function() {
	    	var table = [['GPL','WIN','SUR','FRG','KD','SPT','DMG','CPT','DPT','EXP','EFR','WN7','WN8','SC3','member_count'],
	    				 ['-','WINA','SURA','FRGA','-','SPTA','DMGA','CPTA','DPTA','EXPA','EFRA','WN7A','WN8A','SC3A','-']],
	    		percentage = ['SURA','WINA'],
	    		addStar = ['EFRA','WN7A','WN8A','SC3A'],
	    		self = this;
	    	for(var i = 1;i < 3;i++){
	    		var j = 1;
	    		_.each(table[i-1],function(key){
	    			var c = self.labelClass(key) ,
	    				selector = '#stats-info tr:eq('+i+') td:eq('+j+')'+(c?' span':''),
	    				value = c?{v:self.stats[key],c:c,s:addStar.indexOf(key)>-1,p:percentage.indexOf(key)>-1}:(key=='-'?'-':formatNumber(self.stats[key])),
	    				render = {};
	    			console.log(key, value);
	    			render[selector] = value;
	    			self.r.render(render);
	    			j++;
	    		});
	    	}
	    },
	    
	    renderPercentiles: function() {
	    	var self = this;
	    	
	    	$('a[href="#stats-info"]').on('show',function(){
				if(self.stats && !self.percentiles){
					var toSend = self.type=="players"?{'GPL':'GPL','WIN':'WINA','SUR':'SURA','FRG':'FRGA','KD':'KD','SPT':'SPTA','DMG':'DMGA',
								  'CPT':'CPTA','DPT':'DPTA','EXP':'EXPA','EFR':'EFR','WN7':'WN7','WN8':'WN8','SC3':'SC3'}
								  :{'CGPL':'GPL','CWIN':'WINA','CSUR':'SURA','CFRG':'FRGA','CKD':'KD','CSPT':'SPTA','CDMG':'DMGA',
								  'CCPT':'CPTA','CDPT':'DPTA','CEXP':'EXPA','CEFRA':'EFRA','CWN7A':'WN7A','CWN8A':'WN8A','CSC3A':'SC3A','CMC':'member_count'},
						stats = {};
					_.each(toSend,function(key2,key1){
						stats[key1] = self.stats[key2];
					}),
					stats["type"] = self.type;
					new SiteRequest('percentiles.php','post',stats,function(data){
						var j = 1;
			    		_.each(toSend,function(key2,key1){
			    			var selector = '#stats-info tr:eq(3) td:eq('+j+') span',
			    				render = {};
			    			render[selector] = {v:data[key1],c:key1,p:true,cv:self.stats[key2]};
			    			self.r.render(render);
			    			j++;
			    		});
			    		self.percentiles = true;
					});
				}
			});
	    },
	    
	    renderStatsHistory: function() {
	    	var self = this;
	    	$('a[href="#stats-info"]').on('show',function(){
	    		//console.log(this.stats);
	    		if(!self.stats)return false;
				if(!self.history){
					new ApiRequest('players',self.type,self.wid+'/stats',{},function(res){
						var data = res.data;
			    		data.days.updated_at.push(new Date());
			    		data.days.GPL.push(self.stats.GPL);
			    		data.days.WIN.push(self.stats.WIN);
			    		data.days.SUR.push(self.stats.SUR);
			    		data.days.FRG.push(self.stats.FRG);
			    		data.days.SPT.push(self.stats.SPT);
			    		data.days.DMG.push(self.stats.DMG);
			    		data.days.CPT.push(self.stats.CPT);
			    		data.days.DPT.push(self.stats.DPT);
			    		data.days.EXP.push(self.stats.EXP);
			    		data.days.EFR.push(self.stats.EFR);
			    		data.days.WN7.push(self.stats.WN7);
			    		data.days.WN8.push(self.stats.WN8);
			    		data.days.SC3.push(self.stats.SC3);
			    		self.addHeaderHistoryRow();
		    			for(var i=data.days.updated_at.length-1;i>0;i--){
		    				self.prepareHistoryRowData(data.days,i,i-1);
		    				self.renderStatsHistoryRow(self.historyRowData.length-1);
		    			}
		    			self.historyData = data;
						self.history = true;
						if(self.changesLoaded){
							self.determineChanges();
						}
			    	});
				}
				if(self.type == "clans"){
					if(!self.changesLoaded){
						self.loadChanges(function(){
							self.determineChanges();
						});
					}else if(!self.changesDetermined){
						self.determineChanges();
					}
				}
			});
	    },
	    
	    renderMemberChanges: function() {
	    	var self = this;
	    	
	    	$('a[href="#clan-members"]').on('show',function(){
				if(!self.changesLoaded){
					self.loadChanges();
				}
			});
			$('#changes-page-prev').live('click',function(){
				self.changesPage++;
				$('#clan-members .detail-loading').show();
				self.loadChanges();
				return false;
			});
			$('#changes-page-next').live('click',function(){
				self.changesPage--;
				$('#clan-members .detail-loading').show();
				self.loadChanges();
				return false;
			});
	    },
	    
	    loadChanges: function(callback) {
	    	var self = this;
	    	if(!self.changesPage){self.changesPage = 0;}
	    	
	    	this.constructChangesHeader();
	    	
	    	new ClanApiRequest(['clans',self.wid,'changes',this.changesPage] ,function(data){
	    		if(self.changesPage == 0)self.changes = data.changes;
	    		self.changesPageMax = data.navigation.max;
	    		self.changesPageMin = data.navigation.min;
	    		$('#member_changes_table tbody').html('');
				for(var i in data.changes){
					var change = data.changes[i];
					var date = new Date(change.changed_at);	
					row = '<tr><td><a href="'+URL_BASE+'player.php?wid='+change.player_id+'">'+(change.name || change.player_id)+'</a></td>';
					row += '<td>'+(change.joined?'Joined':'Left')+'</td>';
					row += '<td>'+date.getDate()+'.'+(date.getMonth()+1)+'.'+date.getFullYear()+'</td><td>';
					if(change.previous){
						row += 'from clan <a href="'+URL_BASE+'clan.php?wid='+change.previous.clan_id+'">['+change.previous.clan_tag+']'+change.previous.clan_name+'</a>';	
					}else if(change.next){
						row += 'to clan <a href="'+URL_BASE+'clan.php?wid='+change.next.clan_id+'">['+change.next.clan_tag+']'+change.next.clan_name+'</a>';	
					}else row += '&nbsp;';
					row += '</td></tr>';
					$('#member_changes_table tbody').append(row);
				}
				$('#clan-members .detail-loading').hide();
				self.changesLoaded = true;
				if(callback)callback();
			});
	    },
	    
	    constructChangesHeader: function() {
	    	var now = new Date();
	    	var then = new Date(now.getFullYear(), now.getMonth() - this.changesPage, 1);
	    	var month = this.monthNames[then.getMonth()];
	    	var img = '<img class="detail-loading" src="img/detail-loader.gif"/>';
	    	var link_prev = this.changesPage < (this.changesPageMax || 99) ? '<a href="#" id="changes-page-prev">&lt;</a>' : '';
	    	var link_next = this.changesPage > 0 ? '<a href="#" id="changes-page-next">&gt;</a>' : '';
	    	$('#clan-members h3').html(link_prev+img+' Member changes for '+month+' '+then.getFullYear()+' '+link_next);	    		
	    },
	    
	    determineChanges: function() {
	    	if(!this.historyData)return;
	    	var lastDate = new Date(this.historyData.days.updated_at[0]);
	    	for(var i = this.changes.length-1;i>0;i--){
	    		if(new Date(this.changes[i].changed_at) > lastDate)this.correctChange(this.changes[i]);
	    	}
	    	this.changesDetermined = true;
	    },
	    
	    correctChange: function(change) {
	    	var self = this;
	    	var changeDate = new Date(change.changed_at);
	    	for(var i=0;i<this.historyData.days.updated_at.length;i++){
	    		var tempDate = new Date(this.historyData.days.updated_at[i]);
	    		if(tempDate > changeDate){
	    			var id = i+"-"+(i-1);
	    			new ApiRequest('players','player',change.player_id+'/stats',{},function(res){
	    				var data = res.data;
	    				if(!data.days)return false;
			    		for(var i=0;i<data.days.updated_at.length;i++){
			    			var tempDate = new Date(data.days.updated_at[i]);
			    			if(tempDate > changeDate){
			    				var j = i-1;
			    				break;
			    			}else{
			    				if(i == data.days.updated_at.length - 1)var j = i;
			    			}
			    		}
			    		if(!data.days.GPL[j])j = data.days.updated_at.length-1;
			    		var	f = change.joined?-1:1,
	    					hi = self.historyIdMap[id];
	    				for(key in data.days){
	    					if(key != 'updated_at')
							self.historyRowData[hi][key] += f*data.days[key][j];
	    				}
	    				self.historyRowData[hi].data.EFR[1] += -1*f*data.days.EFR[j];
	    				self.historyRowData[hi].data.WN7[1] += -1*f*data.days.WN7[j];
	    				self.historyRowData[hi].data.WN8[1] += -1*f*data.days.WN8[j];
	    				self.historyRowData[hi].data.GPL[1] += -1*f*data.days.GPL[j];
	    				self.historyRowData[hi].data.MC[1] += -1*f;
	    				console.log(self.historyRowData[hi],data.days.GPL[j]);
	    				$('#history_row-'+id).html(self.buildHistoryRow(hi));
			    	});
	    			break;
	    		}
	    	}
	    },
	    
	    addHeaderHistoryRow: function() {
	    	$("#stats-info tbody").append('<tr><td colspan="'+(this.type=="players"?14:15)+'">Stats ~daily history:</td></th>');
	    },
	    
	    prepareHistoryRowData: function(data, i, j){
	    	var row = {};
	    	for(var key in data){
	    		row[key] = data[key][i] - data[key][j];
	    	}
	    	row.u = [new Date(data.updated_at[i]),new Date(data.updated_at[j])];
	    	row.id = i+"-"+j;
	    	this.historyIdMap[row.id] = this.historyRowData.length;
	    	row.data = {
	    		EFR: [data.EFR[i],data.EFR[j]],
	    		WN7: [data.WN7[i],data.WN7[j]],
	    		WN8: [data.WN8[i],data.WN8[j]],
	    		GPL: [data.GPL[i],data.GPL[j]]
	    	};
	    	if(this.type == "clans")row.data.MC = [data.member_count[i],data.member_count[j]];
	    	this.historyRowData.push(row);	    	
	    },
	    
	    renderStatsHistoryRow: function(i){
	    	var data = this.historyRowData[i],
	    		row = '<tr id="history_row-'+data.id+'">';
	    	row += this.buildHistoryRow(i);
	    	row += "</tr>";
	    	$("#stats-info tbody").append(row);
	    },
	    
	    getAverage: function(data, key,percentage){
	    	var f = percentage?100:1;
	    	return data.GPL!=0?Math.round(data[key]/data.GPL*100*f)/100:'-';
	    },
	    
	    getSpecial: function(data, key,percentage, divideBy){
	    	var f = percentage?100:1;
	    	return divideBy!=0?Math.round(data[key]/divideBy*100*f)/100:'-';
	    },
	    
	    getRating: function(data, key, clan){
	    	var data2 = data.data,
	    		f1 = clan?data2.MC[0]:1,
	    		f2 = clan?data2.MC[1]:1;
	    	return data.GPL!=0?Math.round((data2[key][0]*data2.GPL[0]/f1-data2[key][1]*data2.GPL[1]/f2)/data.GPL*100)/100:'-';
	    },
	    
	    getTdContent: function(value,percentage,keyP,keyC){
	    	var psign = percentage?'%':'';
	    	return '<span class="label label-c'+labelClass(value,this.type=="players"?keyP:keyC)+'">'+value+psign+'</label>';
	    },
	    
	    createStandardTd: function(data, key, percentage){
	    	var value = this.getAverage(data,key,percentage);
	    	return this.getTdContent(value,percentage,key,'C'+key)+'</td><td>';     
	    },
	    
	    buildHistoryRow: function(i){
	    	var data = this.historyRowData[i],
	    		row = '<td title="'+data.u[1].toISOString()+" - "+data.u[0].toISOString()+'">',
	    		timeDiff = Math.round((data.u[0].getTime()-data.u[1].getTime())/(1000*60*60));
	    	if(timeDiff < 13 && data.GPL == 0)return false;
	    	row += data.u[1].getDate()+"."+(data.u[1].getMonth()+1)+" - "+data.u[0].getDate()+"."+(data.u[0].getMonth()+1)+"<br>~"+timeDiff+"hours</td><td>";
	    	row += data.GPL+"</td><td>";
	    	row += this.createStandardTd(data,'WIN',true);
	    	row += this.createStandardTd(data,'SUR',true);
	    	row += this.createStandardTd(data,'FRG',false); 	
			var KD = this.getSpecial(data,'FRG',false,data.GPL-data.SUR);
			row += this.getTdContent(KD, false, 'KD', 'CKD')+'</td><td>';
			row += this.createStandardTd(data,'SPT',false); 
			row += this.createStandardTd(data,'DMG',false); 
			row += this.createStandardTd(data,'CPT',false); 
			row += this.createStandardTd(data,'DPT',false); 
			row += this.createStandardTd(data,'EXP',false); 
			
			if(this.type == "players"){
				EFR = this.getRating(data,'EFR');
				row += this.getTdContent(EFR, false, 'EFR', 'CEFRA')+'</td><td>';
				WN7 = this.getRating(data,'WN7');
				row += this.getTdContent(WN7, false, 'WN7', 'CWN7A')+'</td><td>';
				WN8 = this.getRating(data,'WN8');
				row += this.getTdContent(WN8, false, 'WN8', 'CWN8A')+'</td><td>';
			}else{
				EFR = this.getRating(data,'EFR',true);
				row += this.getTdContent(EFR, false, 'EFR', 'CEFRA')+'</td><td>';
				WN7 = this.getRating(data,'WN7',true);
				row += this.getTdContent(WN7, false, 'WN7', 'CWN7A')+'</td><td>';
				WN8 = this.getRating(data,'WN8',true);
				row += this.getTdContent(WN8, false, 'WN8', 'CWN8A')+'</td><td>';
			}
			SC3 = Math.round(data.SC3*100)/100;
			row += (SC3>0?"+":"")+SC3+"</td>";
			if(this.type == "clans"){
		    	row += "<td>"+(data.member_count>0?"+":"")+data.member_count+"</td>";
	    	}
	    	return row;
	    },
	    
	    calcStats: function(stats) {
	    	var doAverage = ['CPT','DEF','DMG','DPT','EXP','FRG','SPT','SUR','WIN'],
	    		percentage = ['SUR','WIN'],
	    		self = this;
	    		
	    	this.stats = stats;
	    	_.each(doAverage,function(key){
	    		var temp = self.stats[key] / self.stats['GPL'];
	    		if(percentage.indexOf(key) > -1)temp *= 100;
	    		self.stats[key+'A'] = Math.round(temp*100)/100;
	    	});
	    	this.stats.KD = Math.round(stats.FRG/(stats.GPL-stats.SUR)*100)/100;
	    	if(this.type == "clans"){
	    		this.stats.EFRA = roundNumber(this.stats.EFR/stats.member_count,2);
	    		this.stats.WN7A = roundNumber(this.stats.WN7/stats.member_count,2);
	    		this.stats.WN8A = roundNumber(this.stats.WN8/stats.member_count,2);
	    		this.stats.SC3A = roundNumber(this.stats.SC3/stats.member_count,2);
	    	}
	    	
	    	return this.stats;
	    },
	    
	    showCharts: function(){
	    	var self = this;
	    	$('#stats-info th').click(function(){
	    		var cols = $(this).attr('stats-data').split('/');
	    		$('#history_stats_charts').addClass('visible');
	    		$('#history_stats_charts').css({
	    			'top':$(window).scrollTop(),
	    			'background-color': 'rgba(0,0,0,0)',
	    			'textIndent': '0px'
	    		});
	    		$("#history_stats_charts").animate({ textIndent: '0.75px' },{step: function(now,fx) {
			    	$(this).css('background-color','rgba(0,0,0,'+now+')'); 
			    },duration:200});
	    		$('#history_stats_chart_elem').css({
					'top':Math.abs(($('#history_stats_charts').height() - $('#history_stats_chart_elem').outerHeight()) / 2),
    				'left':Math.abs(($('#history_stats_charts').width() - $('#history_stats_chart_elem').outerWidth()) / 2)
				});
				var data = new google.visualization.DataTable();
				data.addColumn('date', 'Date');
				var name = $(this).text();
				if(cols[1] == 'GPL')name = 'Average '+name;
				if(cols[1] == 'member_count')name += ' per player';				
				data.addColumn('number', name);
				var rows = [];
				for(var i in self.historyData.days.updated_at){
					if(cols[0] != 'KD')var value = self.historyData.days[cols[0]][i];
					if(cols[1])value /= self.historyData.days[cols[1]][i];
					if(cols[2])value /= cols[2];
					if(cols[0] == 'KD')var value = self.historyData.days.FRG[i]/(self.historyData.days.GPL[i]-self.historyData.days.SUR[i]);
	    			rows.push([new Date(self.historyData.days.updated_at[i]), value]);
	    		}
	    		data.addRows(rows);
		    	var chart = new google.visualization.LineChart(document.getElementById("history_stats_chart_elem"));
    			chart.draw(data, {displayAnnotations: true});
	    	});
	    	$('#history_stats_charts').click(function(){
	    		$('#history_stats_charts').removeClass('visible');
	    	});
	    }
	});
	
	return Stats;
});
