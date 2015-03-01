define(function(){
	var Renderer = Class.extend({
		
		init: function(player) {
			this.player = player;
			
			if(this.player.name)this.renderRow();
		},
		
		renderRow: function() {
			row = '<tr><td class="no">#</td>';
			row += '<td class="name">'+this.renderPlayerName()+'</td>';
			row += '<td class="stats">'+this.renderStats()+'</td>';
			row += '<td class="hev">'+this.renderTanks(2)+'</td>';
			row += '<td class="med">'+this.renderTanks(1)+'</td>';
			row += '<td class="td">'+this.renderTanks(4)+'</td>';
			row += '<td class="arty">'+this.renderTanks(3)+'</td></tr>';
			$('#vehicle-list tbody').append(row);
			$("#vehicle-list").trigger('update');
			
		},
		
		renderPlayerName: function() {
			ret = '<a href="'+URL_BASE+'player.php?wid='+this.player.wid+'">'+this.player.name+'</a>';
			ret += '<br>(Updated '+formatTime(this.player.updated_at.getTime())+')';
			
			return ret;
		},
		
		renderStats: function() {
			eff = parseFloat(this.player.stats['EFR']);
			ret = '<div class="row"><div class="name-eff">Efficiency:</div><div class="value eff"><span class="label label-c'+labelClass(eff,"EFR")+'">'+eff+'</span></div></div>';
			wn7 = parseFloat(this.player.stats['WN7']);
			ret += '<div class="row"><div class="name-wn7">WN7:</div><div class="value wn7"><span class="label label-c'+labelClass(wn7,"WN7")+'">'+wn7+'</span></div></div>';
			wr = Math.round(this.player.stats['WIN']/this.player.stats['GPL']*10000)/100;
			ret += '<div class="row"><div class="name-wr">Win Ratio:</div><div class="value wr"><span class="label label-c'+labelClass(wr,"WIN")+'">'+wr+'%</span></div></div>';
			dmg = Math.round(this.player.stats['DMG']/this.player.stats['GPL']*100)/100;
			ret += '<div class="row"><div class="name-dmg">Damage:</div><div class="value dmg"><span class="label label-c'+labelClass(dmg,"DMG")+'">'+dmg+'</span></div></div>';
			scr = this.player.stats['SC3'];
			ret += '<div class="row"><div class="name-scr">Score:</div><div class="value eff"><span class="label label-c'+labelClass(scr,"SC3")+'">'+scr+'</span></div></div>';
			gpl = this.player.stats['GPL'];
			ret += '<div class="row"><div class="name-scr">Battles:</div><div class="value gpl"><span class="label label-c'+labelClass(gpl,"GPL")+'">'+gpl+'</span></div></div>';
			
			return ret;
		},
		
		renderTanks: function(type) {
			var sortValue = this.player.tanks[type]?this.player.tanks[type]['tier']*10000+this.player.wins[type]:0;
			ret = '<div class="sort-value">'+("00000"+sortValue).slice(-6)+'</div>';
			if(!this.player.tanks[type])return ret;
			if(this.player.tanks[type]['tier']>0)
				ret += '<div class="tierb">'+toRoman(this.player.tanks[type]['tier'],true)+'</div>';
			
			for(var i in this.player.tanks[type]['tanks']){
				var imgsrc = getNationName(this.player.tanks[type]['tanks'][i]['nation'])+"-"+this.player.tanks[type]['tanks'][i]['name'].toLowerCase(),
					WR = roundNumber(this.player.tanks[type]['tanks'][i]['wins']/this.player.tanks[type]['tanks'][i]['battles']*100,1),
					WRClass = labelClass(WR,this.player.tanks[type]['tanks'][i].name+"-W"),
					winRatio = ' <div class="label label-c'+(WRClass?WRClass:labelClass(WR,"WIN"))+'">('+this.player.tanks[type]['tanks'][i]['battles']+';'+WR+'%)</div>';
				ret += '<div class="tank-section"><img src="'+CONTOUR_URL+imgsrc+'.png" alt="'+this.player.tanks[type]['tanks'][i]['lname']+'" title="'+this.player.tanks[type]['tanks'][i]['lname']+'">'+winRatio+'</div>';
			}
			
			return ret;
		},
		
	});
	
	return Renderer;
});