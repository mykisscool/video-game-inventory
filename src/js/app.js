$(function () {

// Row 1 Dashboards
if (document.getElementById('dashboard')) {

  $.getJSON('api/dashboard/widgets', function (response) {

    var count_options = {
      useEasing: true, 
      useGrouping: true, 
      separator: ',', 
      decimal: '.', 
      prefix: '', 
      suffix: '' 
    };

    // Number of Games
    if (response.number_of_games) {
      new CountUp('number-of-games-value', 0, response.number_of_games, 0, 1, count_options).start();
    }

    // Number of Systems
    if (response.number_of_systems) {
      new CountUp('number-of-systems-value', 0, response.number_of_systems, 0, 1, count_options).start();
    }

    // Games Beaten
    if (response.number_of_games_beaten) {
      new CountUp('number-of-games-completed-value', 0, response.number_of_games_beaten, 0, 1, count_options).start(function () {

        // Clean up star ratings based on the percentage of games beaten
        var earned_stars = 0;

        if (response.percentage_games_beaten < 20) {
          earned_stars = 1;
        }
        else if (response.percentage_games_beaten >= 20 && response.percentage_games_beaten < 40) {
          earned_stars = 2;
        }
        else if (response.percentage_games_beaten >= 40 && response.percentage_games_beaten < 60) {
          earned_stars = 3;
        }
        else if (response.percentage_games_beaten >= 60 && response.percentage_games_beaten < 80) {
          earned_stars = 4;
        }
        else if (response.percentage_games_beaten >= 80) {
          earned_stars = 5;
        }
        else {
          // No need to remove any stars
        }

        if (earned_stars !== 0) {
          $('i', '#percentage-games-beaten').slice(earned_stars).hide();
        }

        $('#percentage-games-beaten > span').text(response.percentage_games_beaten + '%').parent().fadeIn(250);
      });
    }

    // Favorite System
    $('strong i', '#favorite-system-box').fadeOut(250, function () {
      $(this).parents('strong').hide().text(response.favorite_system).animate({ height: 'toggle' }, 250, function () {

        var plural = ((response.favorite_system_games !== 1) ? 's' : '');
        $('#favorite-system-games').text(response.favorite_system_games + ' game' + plural).fadeIn(250);
      });
    });

    // Favorite Genre
    $('strong i', '#favorite-genre-box').fadeOut(250, function () {
      $(this).parents('strong').hide().text(response.favorite_genre).animate({ height: 'toggle' }, 250, function () {

        var plural = ((response.favorite_genre_games !== 1) ? 's' : '');
        $('#favorite-genre-games').text(response.favorite_genre_games + ' game' + plural).fadeIn(250);
      });
    });

    // Last Game Added
    $('strong i', '#last-game-added-box').fadeOut(250, function () {
      $(this).parents('strong').hide().text(response.last_game_added).animate({ height: 'toggle' }, 250, function () {
        $('#last-game-added-date').text(response.last_game_added_date).fadeIn(250);
      });
    });
  });

  // Genre Breakdown
  $.getJSON('api/dashboard/genres', function (response) {

    new Chartist.Bar('#genre-breakdown', {
      labels: response.labels,
      series: response.series
    }, 
    {
      distributeSeries: true,
      seriesBarDistance: 10,
      reverseData: true,
      horizontalBars: true,
      axisY: {
        offset: 125,
        showGrid: false,
        labelInterpolationFnc: function (value, index) {
          return ((value.length <= 15) ? value : value.substr(0, 15) + '...');
        }
      },
      axisX: {
        onlyInteger: true,
      }    
    });

    $('h4 i', '#genre-breakdown-box').fadeOut(250);
  });

  // System Breakdown
  $.getJSON('api/dashboard/systems', function (response) {

    new Chartist.Bar('#system-breakdown', {
      labels: response.labels,
      series: response.series
    }, 
    {
      distributeSeries: true,
      seriesBarDistance: 10,
      reverseData: true,
      horizontalBars: true,
      axisY: {
        offset: 125,
        showGrid: false,
        labelInterpolationFnc: function (value, index) {
          return ((value.length <= 15) ? value : value.substr(0, 15) + '...');
        }        
      },
      axisX: {
        onlyInteger: true
      }
    });

    $('h4 i', '#system-breakdown-box').fadeOut(250);
  });


  // Video Game Timeline
  $.getJSON('api/dashboard/timeline', function (response) {

    new Chartist.Line('#video-game-timeline', {
      labels: response.labels,
      series: [ response.series ]
    },
    {
      showArea: true,
      lineSmooth: Chartist.Interpolation.simple({
        divisor: 7
      }),
      high: _.max(response.series) +1,
      fullWidth: true,
      chartPadding: {
        right: 40
      },
      axisY: {
        onlyInteger: true
      },
      axisX: {
        labelInterpolationFnc: function (value, index) {
          if (response.series.length > 25) {
            return '\'' + value.slice(2, 4);
          }
          else {
            return value;
          }
        }
      }
    }, 
    [
      ['screen and (max-width: 1199px)', {
        axisX: {
          labelInterpolationFnc: function (value, index) {

            // @TODO Would love to concoct a formula here to more precisely plot labels on this axis
            value = '\'' + value.slice(2, 4);
            return index % 2  === 0 ? value : null;
          }
        }
      }],      
      ['screen and (max-width: 991px)', {
        axisX: {
          labelInterpolationFnc: function (value, index) {

            // @TODO Ditto
            value = '\'' + value.slice(2, 4);
            return index % 3 === 0 ? value : null;
          }
        }
      }],
      ['screen and (max-width: 767px)', {
        axisX: {
          labelInterpolationFnc: function (value, index) {

            // @TODO Ditto
            value = '\'' + value.slice(2, 4);
            return index % 5  === 0 ? value : null;
          }
        }
      }]
    ]);

    $('h4 i', '#video-game-timeline-box').fadeOut(250);
  });
} // dashboard

// Catalog
if (document.getElementById('catalog')) {

  $('#add-record').tooltip();

  Handlebars.registerHelper('prettyDate', function (date) {
    if (moment(date).isValid()) {
      return '<span class="hidden">' + moment(date).format('YYYYMMDDHHmmss') + '</span>' 
        + moment(date).format('MMM. Do, YYYY');
    }
    else {
      return '-';
    }
  });

  Handlebars.registerHelper('prettyList', function (list) {
    list = list || '';

    // Workaround for parsing and splitting a string with commas built in
    var a = list.replace(new RegExp(', ', 'g'), '||').split(',');
    for (var i=0; i < a.length; i++) {
      a[i] = a[i].replace(/\|\|/g, ', ');
    }
    
    if (a.length > 1) {
      var h = '<ol>';
      _.each(a, function (g) {
        h += '<li>' + g.trim() + '</li>';
      })
      return h + '</ol>';
    }
    else {
      if (list.length) {
        return list;
      }
      else {
        return '-';
      }
    }
  });

  Handlebars.registerHelper('toHTML', function (s) {
    return s;
  });

  var games = new Games();
  games.fetch({
    success: function () {
      var gamesView = new GamesView({ collection: games }),
          inventory = gamesView.render().$parent;

      $('.loader').fadeOut(200, function () {
        $('.box', '#catalog').append($(inventory).fadeIn(400));
      });
    }
  });

  Backbone.history.start({ root: '/catalog/' });
  _.extend(Backbone.Model.prototype, Backbone.Validation.mixin);
} // catalog

});
