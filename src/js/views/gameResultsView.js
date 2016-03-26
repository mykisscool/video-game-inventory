var GameResultsView = Backbone.View.extend({
	id: 'datatable-search-results',
	tagName: 'table',
	className: 'table table-vcenter table-bordered table-striped table-hover',
	initialize : function (options) {
		this.parent = options.parent;
	},	
	render: function () {
		var view = this;

		$.fn.dataTable.ext.pager.numbers_length = 5;
		this.$el.append(Templates.resultsHeading()).DataTable({
			responsive: true,
			filter: false,
			lengthChange: false,
			order: [[ 0, 'asc' ]],
			pageLength: 25,
			language: {
				emptyTable: 'No search results here ...'
			},
			initComplete: function () {
				var table = this;
				
				view.collection.each(function (result) { 
					var gameResultView = new GameResultView({ model: result, parent:view.parent });
					table.DataTable().row.add(gameResultView.render().$el);
				});
			}			
		}).draw();

		new $.fn.dataTable.Responsive(this.el);

		// Since DataTables creates wrapper HTML- let's add a "$parent" property
		// to the view object and assign a cached jQuery object to it.
		this.$parent = this.$el.parents('#' + this.id + '_wrapper');
		return this;
	}
});
