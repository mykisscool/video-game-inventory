var GameSearchView = Backbone.View.extend({
  id: 'search-form',
  tagName: 'div',
  events: {
    'click #close-form': 'closeFormButton',
    'click #search' : 'searchGameDatabase'
  },
  render: function () {
    this.$el.append(Templates.searchForm());
    return this;
  },
  closeFormButton: function (e) {
    this.$el.slideUp(200, function () {
      this.remove();
    });
    $('body').css('overflow-y', 'auto');
  },
  searchGameDatabase: function (e) {
    e.preventDefault();

    var screen = this,
      data = $('form', this.$el).serializeArray();
    
    if ('' !== data[0].value || '' == data[1].value) {
      return;
    }

    $.ajax({
      method: 'get',
      url: '../api/giantbomb/search/' + data[1].value,
      beforeSend: function () {
        $(e.target).prop('disabled', true)
        $(e.target).parents('.input-group-btn').prev(':text').prop('disabled', true);

        $('.fa-circle-o-notch', e.target).css('display', 'inline-block');
        $('.fa-search', e.target).hide();
      },
      complete: function () {
        $(e.target).prop('disabled', false)
        $(e.target).parents('.input-group-btn').prev(':text').prop('disabled', false);

        $('.fa-circle-o-notch', e.target).hide();
        $('.fa-search', e.target).css('display', 'inline-block');
      },
      success: function (response) {
        var presponse, results = new Results();

        // Generic error
        try {
          presponse = JSON.parse(response);
        }
        catch (e) {
          // @TODO Better error handling for empty JSON response.  Timeout issue?
          alert('A generic error has occurred.  Please refresh and try again.');
        }

        _.map(presponse.results, function (title) {
          _.map(title.platforms, function (platform) {
            results.add(new Result({ 
              id: title.id,
              title: title.name,
              system: platform.name,
              released: title.original_release_date
            }));
          });
        });

        var gameResultsView = new GameResultsView({ collection: results, parent:screen }),
          $form = $('form', screen.el),
          $table = $form.children('div.dataTables_wrapper');

        if ($table.length) {
          $table.fadeOut(200, function () {
            $table.remove();
            $form.append($(gameResultsView.render().$parent).fadeIn(400));
          });
        }
        else {
          $form.append($(gameResultsView.render().$parent).fadeIn(400));
        }
      },
      error: function () {
        // @TODO error handling for interfacing with GiantBomb API or my API
      }
    });
  }
});
