/**
 * View for the board points
 */
itsallagile.View.TicketUser = Backbone.View.extend({
    tagName: 'div',
    id: 'ticketUser',
    ticketView: null,
    users: [],
    template: '<div class="modal hide fade">' +
        '<div class="modal-header">' +
        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
        '<h3>Assign User</h3>' +
        '</div>' +
        '<div class="modal-body">' +
        '<select id="assignedUser">' +
        '</select>' +
        '</div>' +
        '<div class="modal-footer">' +
        '<a class="btn" data-dismiss="modal">Cancel</a>' +
        '<a id="ticketAssignMe" class="btn btn-primary ticket-user-save">Assign Me</a>' +
        '<a id="ticketAssignUser" class="btn btn-primary ticket-user-save">Assign User</a>' +
        '</div>' +
        '</div>',
    templateOption: '<option value="<%= id %>"<%= selected %>><%= name %></option>',
    templateOptions: '<option value="">Unassigned</option>' +
        '<% _.forEach(users, function(user) { %>' +
        '<option value="<%= user.id %>"<%= user.selected %>><%= user.name %></option>' +
        '<% }); %>',

    events: {
        'click .ticket-user-save' : 'assignUser'
    },

    initialize: function(options) {
        this.users = options.users;
    },

    assignUser: function(e) {
        // Which button triggered the event
        var source = e.currentTarget.id;

        // Find the userId based on source
        if (source == 'ticketAssignMe') {
            var userId = itsallagile.Controller.Scrumboard.user.id;
        } else {
            var userId = this.$el.find('#assignedUser').val();
        }

        // hide the modal
        this.$el.find('.modal').modal('hide');

        // Save the ticket with the new assigned user
        if (userId != this.ticketView.model.getAssignedUserId()) {
            // Quick hack to fix the problem with not updating when unassigning user
            if (userId === '') {
                this.ticketView.model.unset('assigned_user');
            }

            this.ticketView.model.save('assignUserId', userId, {success: this.ticketView.changeSuccess});
        }
    },

    showModal: function(ticketView) {
        this.ticketView = ticketView;
        var data = [];

        // Find the currently assigned user
        var assignedUser = ticketView.model.getAssignedUserId();

        // Setup data for template
        _.forEach(this.users, function (user) {
            var item = {id: user.id, name: user.name, selected: ''};
            if (user.id == assignedUser) {
                item.selected = 'selected="selected"';
            }
            data.push(item);
        });

        // Get the options in html
        var optionsHtml = _.template(this.templateOptions, {users: data});

        // Put the options in the select
        this.$el.find('#assignedUser').html(optionsHtml);

        // Show the modal
        this.$el.find('.modal').modal('show');
    },

    render: function() {
        this.$el.html(_.template(this.template, {}));
        return this;
    }
});