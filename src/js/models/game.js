var Game = Backbone.Model.extend({
	idAttribute: 'id',
	urlRoot: '../api/games',
	validation: {
		title: {
			required: true,
			maxLength: 128,
			msg: 'An invalid title was detected.'
		},
		system: {
			required: true,
			maxLength: 128,
			msg: 'An invalid platform was detected.'
		},		
		image: {
			required: false,
			maxLength: 256,
			msg: 'An invalid image was detected.'
		},
		genre: {
			required: false,
			maxLength: 256,
			msg: 'An invalid genre was detected.'
		},
		developer: {
			required: false,
			maxLength: 256,
			msg: 'An invalid developer was detected.'
		},
		released_on: {
			required: false,
			length: 10,
			msg: 'An invalid release date was detected.'
		},
		notes: {
			required: false,
			maxLength: 256,
			msg: 'Please enter valid notes.'
		},
		completed: {
			required: false,
			oneOf: [0, 1],
			msg: 'Please specify if the game was beaten or not.'
		}
	}	
});
