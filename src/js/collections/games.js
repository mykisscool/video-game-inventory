var Games = Backbone.Collection.extend({
	model: Game,
	url: '../api/games'
});
