define(function(){
	var ChartsManager = Class.extend({
		
		init: function() {
			this.pieOptions = options = {height: 350, width: 350, chartArea:{'width': '80%', 'height': '80%'}, backgroundColor: '#272B30', titleTextStyle: {color: '#e2e2e2'}, legend:{position: 'none'}};
		},
	    
	    show: function(total) {
			for(var i=1;i<5;i++){
				total.vehs[i].sort(function(a,b){
					if(a.count < b.count)return 1;
					if(a.count == b.count)return 0;
					if(a.count > b.count)return -1;
				});
				this.renderTops(i,total.tops[i]);
				this.renderTanks(total.vehs[i],i);
				this.drawPieChart(total.vehs[i],i);
			}
	    },
	    
	    renderTops: function(i,count){
	    	$("#"+i+"-veh-row h3").append(" <span title='# players with this type of tank'>("+count+")</span>");
	    },
		
		renderTanks: function(tanks,t) {
			var gpl = 0, win = 0, count = 0;
			for(var i in tanks){
				tank = tanks[i];
				var name = tank.name,
					div = '<div class="veh-row-elem">';
				div += '<img src="'+tank.icon+'" alt="'+name+'" title="'+name+'"><br>';
				div += name+"<br>";
				var WR = roundNumber(tank.wins/tank.battles*100,2);
				var GPL = Math.round(tank.battles/tank.count*100)/100;
				var GPLClass = labelClass(GPL,tank.id+"-B");
				div += '<span class="first label label-c0">'+tank.battles+'</span><br>';
				div += '<span class="label label-c'+(GPLClass?GPLClass:0)+'">'+GPL+'</span><br>';
				var WRClass = labelClass(WR,tank.id+"-W");
				div += '<span class="label label-c'+(WRClass?WRClass:labelClass(WR,"WIN"))+'">'+WR+'%</span><br>';
				div += '<span class="label label-c0">'+tank.count+'</span><br>';
				div += '</div>'
				$("#"+t+"-veh-row").append(div);
				gpl += tank.battles;
				win += tank.wins;
				count += tank.count;
			}
			WR = gpl?(roundNumber(win/gpl*100,2)):"-",
			$("#"+t+"-battles").html(gpl);
			$("#"+t+"-battles-a").html(gpl?(Math.round(gpl/count*100)/100):"-");
			$("#"+t+"-winrate").html(WR+"%").addClass("label-c"+labelClass(WR,"WIN"));
			$("#"+t+"-count").html(count);
		},
		
		drawPieChart: function(tanks,t){
	    	var options = this.pieOptions;
	    	var array1 = new Array();
	    	array1.push(['Vehicle', 'Number of people owning this tank']);
	    	for(var i in tanks){
    			value = tanks[i].count;
    			array1.push([tanks[i].name, value]);
    		}
	    	var data1 = google.visualization.arrayToDataTable(array1);
	    	var chart1 = new google.visualization.PieChart(document.getElementById(t+'-chart'));
	    	chart1.draw(data1, options);
	    },
		
	});
	
	return ChartsManager;
});