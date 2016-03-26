var GameDeleteView = Backbone.View.extend({
	id: 'delete-modal',
	className: 'modal fade',
	attributes: function () {
		return {
			'role': 'dialog',
			'tabindex': '-1'
		}
	},	
	events: {
		'hidden.bs.modal': 'teardown',
		'click .btn-danger': 'deleteGame'
	},
	initialize: function () {
		_.bindAll(this, 'show', 'teardown', 'render');
		this.render();
	},
	show: function () {
		this.$el.modal('show');
	},
	teardown: function () {
		this.$el.data('modal', null);
		this.remove();
	},
	render: function () {
		this.$el.html(Templates.deleteModal(this.model.toJSON())).modal({
			show: false
		});
		return this;
	},
	deleteGame: function (e) {
		$('.fa-circle-o-notch', e.target).css('display', 'inline-block');
		$('.fa-trash', e.target).hide();

		this.model.destroy({
			dataType: 'text',
			success: function (model) {
				$('.container-fluid', '#catalog').prepend(Templates.alert({
					type: 'info',
					message: '<em>' + model.get('title') + '</em> was successfully removed from your inventory.'
				}));
			},
			error: function () {
				// @TODO error handling when DELETE fails
			}
		});

		this.$el.modal('hide');
		$('html, body').animate({ scrollTop: 0 }, 250);
	}
});
