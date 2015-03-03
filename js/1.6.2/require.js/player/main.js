define(["models/player"], function(Player) {
	
	$(document).ready(function(){
		var player = new Player(WID);
		player.loadFromAPI();		
	});
});