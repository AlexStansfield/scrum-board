/**
 * View for the board points
 */
itsallagile.View.TicketHistory = Backbone.View.extend({
    tagName: 'div',
    id: 'ticket-history',
    model: null,
    template: '<div class="modal hide fade">' +
        '<div class="modal-header">' +
        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>' +
        '<h3>Ticket History</h3>' +
        '</div>' +
        '<div class="modal-body">' +
        '<ul id="ticket-history-list"></ul>' +
        '</div>' +
        '<div class="modal-footer">' +
        '<a class="btn" data-dismiss="modal">Close</a>' +
        '</div>' +
        '</div>',
    templateItems: '<li><%= created %> - created</li>' +
        '<% _.forEach(history, function(item) { %>' +
        '<li><%= item.created %> - <%= item.user %> changed <%= item.field %> to <%= item.new_value %></li>' +
        '<% }); %>',

    /**
     * Render the view
     */
    render: function() {
        this.$el.html(_.template(this.template));
        return this;
    },

    /**
     * Show modal
     */
    showModal: function(ticket) {
        var history = ticket.get('history');
        var created = ticket.get('created');
        var list = $('ul#ticket-history-list', this.$el);

        // Get the html list of history items
        var itemsHtml = _.template(this.templateItems, {created: created, history: history});
        list.html(itemsHtml);

        // Show the modal
        this.$el.find('.modal').modal('show');
    }
});