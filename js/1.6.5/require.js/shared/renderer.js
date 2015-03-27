define(function(){
	var Renderer = Class.extend({
		
		init: function(player) {
			this.p = player;
	    },
	    
	    render: function(data) {
	    	_.each(data,function(value,selector){
	    		if(typeof value === 'object'){
	    			var p = value.p?'%':'',
	    				s = value.s?'*':'';
	    			$(selector).html(value.v+p+s).addClass("label-c"+labelClass(value.cv?value.cv:value.v,value.c));
	    			if(s)$(selector).attr('title','Per player');
	    		}else $(selector).html(value);
	    	});
	    },
	    
	    renderTanks: function(tanks,t){
	    	var gpl = 0, win = 0, scr = 0;
			for(var i in tanks){
				tank = tanks[i];
				var name = tank.name,
					div = '<div class="veh-row-elem">';
				div += '<img src="'+tank.icon+'" alt="'+name+'" title="'+name+'"><br>';
				div += name+"<br>";
				var WR = roundNumber(tank.wins/tank.battles*100,2);
				var SCR = tank.tier == 10?roundNumber(calcCS3(tank.battles,tank.wins,this.p.stats.WN7,t<3?1000:900),2):0;
				var GPLClass = labelClass(tank.battles,tank.id+"-B");
				div += '<span class="first label label-c'+(GPLClass?GPLClass:0)+'">'+tank.battles+'</span><br>';
				var WRClass = labelClass(WR,tank.id+"-W");
				div += '<span class="label label-c'+(WRClass?WRClass:labelClass(WR,"WIN"))+'">'+WR+'%</span><br>';
				var SCRlass = labelClass(SCR,tank.id+"-S");
				div += '<span class="label label-c'+(SCRlass?SCRlass:scrClassTank(SCR))+'">'+SCR+'</span><br>';
				div += '</div>'
				$("#"+t+"-veh-row").append(div);
				gpl += tank.battles;
				win += tank.wins;
				scr += SCR,2;
			}
			var WR = gpl?roundNumber(win/gpl*100,2):'-';
			$("#"+t+"-battles").html(gpl);
			$("#"+t+"-winrate").html(WR+"%").addClass("label-c"+labelClass(WR,"WIN"));
			$("#"+t+"-score").html(roundNumber(scr,2)).addClass("label-c"+scrClassType(scr,t));
	    },
	    
	    calcCS3: function(){
	    	
	    },
		
	});
	
	return Renderer;
});