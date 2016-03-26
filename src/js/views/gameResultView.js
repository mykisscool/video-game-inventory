var GameResultView = Backbone.View.extend({
	tagName: 'tr',	
	events: {
		'click button': 'addGame'
	},
	initialize : function (options) {
		this.parent = options.parent;
	},	
	render: function () {
		this.$el.html(Templates.resultRow(this.model.toJSON()));
		return this;		
	},
	addGame: function (e) {
		e.stopPropagation();
		var searchFormView = this.parent, gameToBeAdded = this.model;

		$.ajax({
			method: 'get',
			url: '../api/giantbomb/get/' + this.model.get('id'),
			beforeSend: function () {
				$(e.target).hide().after('<i class="fa fa-spinner fa-pulse pull-right"></i>');
			},
			success: function (response) {
				var results, properties = {};

				// Generic error
				try {
					results = JSON.parse(response)['results'];
				}
				catch (e) {
					// @TODO Better error handling for empty JSON response.  Timeout issue?
					alert('A generic error has occurred.  Please refresh and try again.');
				}

				properties.title = results.name;
				properties.system = gameToBeAdded.get('system');

				properties.image = ((results.image === null) ? null : results.image['super_url']);

				properties.genre = _.map(results.genres, function (genre) {
					return genre['name'];
				}).join(',') || null;	

				properties.developer = _.map(results.developers, function (developer) {
					return developer['name'];
				}).join(',') || null;				

				properties.description = ((results.deck === null) ? null : results.deck);

				properties.released_on = ((moment(results.original_release_date).isValid()) 
					? moment(results.original_release_date).format('YYYY-MM-DD') : null);

				// Validate model
				var game = new Game();
				game.set(properties, { validate: true });

				var isValid = game.isValid();
				if (isValid) {
					new Games().create(game, {
						success: function (model, responseText, response) {

							// Update DataTable
							game.set('id', response.xhr.responseText);
							game.set('image', ((properties.image !== null) ? properties.image.split('/').pop() : null));
							bus.trigger('newGameAdded', game);
					
							// Update parent view
							$('.container-fluid', '#catalog').prepend(Templates.alert({
								type: 'info',
								message: '<em>' + game.get('title') + '</em> was successfully added to your inventory.'
							}));

							searchFormView.closeFormButton();
						},
						error: function (model, response) {

							// Update parent view
							$('.container-fluid', '#catalog').prepend(Templates.alert({
								type: 'warning',
								message: '<em>' + game.get('title') + '</em> ' + response.responseText + '.'
							}));

							searchFormView.closeFormButton();
						}
					});
				}
				else {

					var errors = _.map(game.validationError, function (message) {
						return '<p>' + message + '</p>';
					}).join().replace(',', '')

					// Update parent view
					$('.container-fluid', '#catalog').prepend(Templates.alert({
						type: 'danger',
						message: errors
					}));

					searchFormView.closeFormButton();
				}

				$('html, body').animate({ scrollTop: 0 }, 250);
			},
			error: function () {
				// @TODO error handling for interfacing with GiantBomb API or my API
			}			
		});
	}
}), bus = _.extend({}, Backbone.Events);
