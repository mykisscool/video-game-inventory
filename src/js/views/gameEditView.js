var GameEditView = Backbone.View.extend({
  id: 'edit-modal',
  className: 'modal fade',
  attributes: function () {
    return {
      'role': 'dialog',
      'tabindex': '-1'
    }
  },
  events: {
    'hidden.bs.modal': 'teardown',
    'click .btn-primary': 'editGame'
  },
  initialize: function (options) {
    this.parent = options.parent;
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
    this.$el.html(Templates.editModal(this.model.toJSON())).modal({
      show: false
    });
    return this;
  },
  editGame: function (e) {
    $('.fa-circle-o-notch', e.target).css('display', 'inline-block');
    $('.fa-pencil', e.target).hide();

    var changes = {};
    _.each($('form', this.el).serializeArray(), function (field) {
      changes[field.name] = ((!isNaN(field.value) && field.value.trim().length > 0) 
        ? parseInt(field.value) : (field.value || null));
    });

    this.model.set(changes, { validate: true });
    var isValid = this.model.isValid();

    if (isValid) {
      this.model.save(changes, {
        dataType: 'text',
        success: function (model) {
          $('.container-fluid', '#catalog').prepend(Templates.alert({
            type: 'info',
            message: '<em>' + model.get('title') + '</em> was successfully updated.'
          }));
        },
        error: function () {
          // @TODO error handling when PUT fails
        }
      }); 
    }
    else {
      var errors = _.map(this.model.validationError, function (message) {
        return '<p>' + message + '</p>';
      }).join().replace(',', '');

      $('.container-fluid', '#catalog').prepend(Templates.alert({
        type: 'danger',
        message: errors
      }));
    }

    this.$el.modal('hide');
    this.parent.$el.find('td:first').trigger('click');
    $('html, body').animate({ scrollTop: 0 }, 250);
  }
});
