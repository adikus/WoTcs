google.load("visualization", "1", {packages:["corechart"]});

options = {height: 250, backgroundColor: '#272B30', titleTextStyle: {color: '#e2e2e2'}, legend:{textStyle:{color: '#ffffff'}}};

function drawChartsHeavy(){
	drawCharts(2,'Heavys','heavys');
}

function drawChartsMed(){
	drawCharts(1,'Meds','meds');
}

function drawChartsTD(){
	drawCharts(3,'TDs','tds');
}

function drawChartsArty(){
	drawCharts(4,'Artys','artys');
}

function drawCharts(type,title,id){
	
	options.title = title+' by count';
	var data = [['Vehicle', 'Number of people owning this tank']];
	for(i in tanks[type]){
		data.push([tanks[type][i]['name'],parseInt(tanks[type][i]['battles']/tanks[type][i]['battles_average'])]);
	}
	var chart1 = new google.visualization.PieChart(document.getElementById(id+'_count_chart'));
	chart1.draw(google.visualization.arrayToDataTable(data), options);
	
	options.title = title+' by battles per player';
	data = [['Vehicle', 'Battles per player']];
	for(i in tanks[type]){
		data.push([tanks[type][i]['name'],tanks[type][i]['battles_average']]);
	}
	var chart2 = new google.visualization.PieChart(document.getElementById(id+'_battles_chart'));
    chart2.draw(google.visualization.arrayToDataTable(data), options);

    options.title = title+' by win ratio';
    options.vAxis = {textStyle: {color: '#ffffff'}};
    options.hAxis = {textStyle: {color: '#ffffff'}};
	data = [['Vehicle', 'Win ratio']];
	for(i in tanks[type]){
		data.push([tanks[type][i]['name'],tanks[type][i]['winrate']]);
	}

    var chart3 = new google.visualization.ColumnChart(document.getElementById(id+'_win_chart'));
    chart3.draw(google.visualization.arrayToDataTable(data), options);
	
}

function drawPlayerDataChart(sdata,name,id){
	options.title = name;
    options.vAxis = {textStyle: {color: '#ffffff'}};
    options.hAxis = {textStyle: {color: '#ffffff'}};
    for(i in sdata.data){
    	if(i == 0)sdata.data[i][0] = name;
    	else {
    		sdata.data[i][0] = parseFloat(sdata.data[i][0]);
    		sdata.data[i][1] = parseInt(sdata.data[i][1]);
    	}
    }
    console.log(sdata.average,sdata.stdev);

    var chart = new google.visualization.ColumnChart(document.getElementById(id));
    chart.draw(google.visualization.arrayToDataTable(sdata.data), options);
}
