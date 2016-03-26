var GameView = Backbone.View.extend({
	tagName: 'tr',
	events: {
		'click td:first': 'showControls',
		'click .view-artwork': 'viewGameArtwork',
		'click .edit-game': 'editGameForm',
		'click .delete-game': 'confirmDeleteGame',
	},
	render: function () {
		this.$el.html(Templates.gameRow(this.model.toJSON()));
		return this;		
	},
	showControls: function (e) {
		if ($(e.target).parents('tr').hasClass('parent')) {
			if ($(e.target).hasClass('actions')) { // <div class="actions" /> was clicked
				$(e.target).hide();
			}
			else {
				$('.actions', e.target).hide();
			}
		}
		else {
			$('.actions', e.target).show();
		}
	},
	viewGameArtwork: function (e) {
		e.stopPropagation();
		$.magnificPopup.open({
			items: {
				src: $(e.currentTarget).data('mfp-src')
			},
			type: 'image'
		});		
	},	
	editGameForm: function (e) {
		e.stopPropagation();
		$('body').append(new GameEditView({ model: this.model, parent: this}).show());
	},
	confirmDeleteGame: function (e) {
		e.stopPropagation();
		$('body').append(new GameDeleteView({ model: this.model }).show());
	}
});
