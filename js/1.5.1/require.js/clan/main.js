define(["models/clan","models/charts_manager",'./../shared/stats'], function(Clan,ChartsManager,Stats) {
	
	
	$(document).ready(function(){
		var clan = new Clan(WID);
		var chartsManager = new ChartsManager();
		var src = WOT_BASE+"media/clans/emblems/clans_"+WID.toString().charAt(0)+"/"+WID+"/emblem_64x64.png";
		
		
					
		var sm = new Stats('clan',WID);
		
		sm.renderStatsHistory();
		sm.renderMemberChanges();
		sm.showCharts();
		
		setInterval(function(){
			
			clan.updateLoadBar();
			
			switch(clan.status){
			case 0:
				if(clan.wait > 0){
					clan.wait -= 100;
					break;
				}
				if(clan.retry < 3)clan.loadInfo();
				break;
			case 1:
				if(WID > 2500000000 && WID < 3000000000){
					$('#alert_msg').removeClass().addClass('alert alert-error').html(
						'We are sorry but there is a problem with Wargaming\'s API at a moment.<a class="close" data-dismiss="alert" href="#">&times;</a>'
					).alert();
					$('.hero-unit h1 img').attr('src',src);
					return false;
				}	
				if(clan.wait > 0){
					clan.wait -= 100;
					break;
				}
				if(clan.retry < 1000)clan.loadPlayers();
				else{
					if(clan.notUpdated() > 10){
						clan.retry = 0;
						clan.loadPlayers(false,false,true);
					}
					else {
						clan.addNotUpdatedPlayers();
						clan.status = 2;
					}
				}
				break;
			case 2:
				if(clan.notUpdated() > 0){
					clan.last = 0;
					clan.status = 1;
					clan.retry = 0;
					clan.loadPlayers(false,true,true);
				}
				for(var i in clan.members){
					if(!clan.members[i].updated() && clan.members[i].retry < 15 && !clan.members[i].waiting){
						clan.members[i].loadData();
						break;
					}else if(clan.members[i].updated() && !clan.members[i].loaded){
						clan.members[i].fromData();
						//break;
					}
				}
				if(clan.membersLoaded() != false)clan.status++;
				break;
			case 3:
				clan.status++;
				if(clan.total){
					clan.showStats(clan.total);
					clan.countTops();
					chartsManager.show(clan.total);
					
					sm.calcStats(clan.total.stats_current);
					
					sm.renderStatsTable();
					sm.renderPercentiles();
		
				}
				console.log('Loading done.');
				$('#alert_msg').html('Loading done.<a class="close" data-dismiss="alert" href="#">&times;</a>');
				$('#alert_msg').alert();
				$('.hero-unit h1 img').attr('src',src);
				break;
			}
			
		},100);
	});
});