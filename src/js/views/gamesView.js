var GamesView = Backbone.View.extend({
  id: 'datatable-games',
  tagName: 'table',
  className: 'table table-vcenter table-bordered table-striped table-hover',
  events: {
    'click #add-record': 'searchFormLink'
  },
  initialize: function (options) {
    bus.on('newGameAdded', this.addGame, this);
    this.collection.on('change', this.changeGame, this);
    this.collection.on('remove', this.removeGame, this);
  },
  render: function () {
    var view = this;

    $.fn.dataTable.ext.pager.numbers_length = 5;
    this.$el.append(Templates.inventoryHeading()).DataTable({
      responsive: true,
      order: [[ 8, 'desc' ]],
      pageLength: 50,
      language: {
        emptyTable: 'No games here ...'
      },
      initComplete: function () {
        var table = this;
        
        view.collection.each(function (game) { 
          var gameView = new GameView({ model: game });
          table.DataTable().row.add(gameView.render().$el);
        });
      }
    }).draw();

    new $.fn.dataTable.Responsive(this.el);

    // Since DataTables creates wrapper HTML- let's add a "$parent" property
    // to the view object and assign a cached jQuery object to it.
    this.$parent = this.$el.parents('#' + this.id + '_wrapper');
    return this;
  },
  searchFormLink: function (e) {
    $('body').append($(new GameSearchView().render().$el).fadeIn(400)).css('overflow-y', 'hidden');
  },
  addGame: function (game) {
    this.collection.add(game);
    var gameView = new GameView({ model: game });
    this.$el.DataTable().row.add(gameView.render().$el).draw();
  },
  changeGame: function (game) {
    var $row = this.$el.find("td[data-game='" + game.get('id') + "']").parent(),

      // @TODO Right now I know the column location of the only two columns being updated.
      // This logic needs to change should I decide to update more or all columns.
      $col_completed = $row.find('td').eq(6),
      $col_notes = $row.find('td').eq(7),

      completed = ((game.get('completed')) ? 'Yes' : 'No'),
      notes = (((!game.get('notes') || game.get('notes').toString().trim().length > 0)) ? game.get('notes') : '-');

    // fnUpdate() is destructive as the DataTable loses responsiveness
    this.$el.DataTable().row($row).cell($col_completed).data(completed).draw();
    this.$el.DataTable().row($row).cell($col_notes).data(notes).draw();
  },
  removeGame: function (game) {
    var $row = this.$el.find("td[data-game='" + game.get('id') + "']").parent();
    this.$el.DataTable().row($row).remove().draw();
  }
});
