/**
 * Board view
 */
itsallagile.View.Board = Backbone.View.extend({

    tagName: 'table',
    className: 'board',
    ticketStatuses: [],
    storyStatuses: [],
    statusHeaderView: null,
    storyViews: {},


    /**
     * Load the fixed elements on the board
     * and do initial bindings
     */
    initialize: function(options) {
        this.ticketStatuses = options.ticketStatuses;
        this.storyStatuses = options.storyStatuses;
        this.statusHeaderView = new itsallagile.View.StatusHeader({statuses : this.ticketStatuses});
        this.model.bind('change', this.render, this);
        var stories = this.model.get('stories')
        stories.bind('add', this.renderStory, this);
        stories.bind('remove', this.render, this);
        stories.bind('reset', this.render, this);

        _.bindAll(this);

        return this;
    },

    /**
     * Bind to events coming in from the socket connection
     * We do this at board level as it has the easiest access
     * to sub objects
     */
    bindSocketEvents: function() {
        var socket = itsallagile.socket;
        if (typeof socket !== 'undefined') {
            socket.on('ticket:change', this.onRemoteTicketChange);
            socket.on('ticket:move', this.onRemoteTicketMove);
            socket.on('ticket:create', this.onRemoteTicketCreate);
            socket.on('ticket:delete', this.onRemoteTicketDelete);
            socket.on('story:add', this.onRemoteStoryAdd);
            socket.on('story:delete', this.onRemoteStoryDelete);
            socket.on('story:update', this.onRemoteStoryUpdate);
        }
    },

    /**
     * Render the board by rendering its toolbar and header
     * and calling render on all stories
     */
    render: function() {
        this.$el.html('');
        this.$el.append($('<thead>').append(this.statusHeaderView.render().el));
        var stories = this.model.get('stories');
        if (stories !== null) {
            stories.forEach(this.renderStory, this);
        }

        return this;
    },

    /**
     * Render a single story view
     */
    renderStory: function(story) {
        var storyView = new itsallagile.View.Story({
            model: story,
            ticketStatuses: this.ticketStatuses,
            storyStatuses: this.storyStatuses
        });

        storyView.on('moveTicket', this.onMoveTicket, this);
        storyView.on('deleteStory', this.onDeleteStory, this);
        this.storyViews[story.get('id')] = storyView;
        this.$el.append(storyView.render().el);
    },

    /**
     * Handle moving a ticket between stories
     * Looks at bit hacky, but I couldnt find an easier way
     * of moving an object between collections
     */
    onMoveTicket: function(ticketId, originStoryId, status, newStoryId) {
        var stories = this.model.get('stories');
        var originStory = stories.get(originStoryId);
        var newStory = stories.get(newStoryId);
        var ticket = originStory.get('tickets').get(ticketId);

        originStory.get('tickets').remove(ticket);
        ticket.save({status: status}, {silent:true});
        newStory.get('tickets').add(ticket);

        if (typeof itsallagile.socket !== 'undefined') {
            itsallagile.socket.emit('boardEvent', itsallagile.roomId, 'ticket:move',
                {ticket: ticket, originStoryId: originStoryId});
        }
    },

    //REMOTE EVENTS
    //--------------------------------------------------------

    /**
     * Handle a ticket moved between stories by another user
     */
    onRemoteTicketMove: function(params) {
        var ticketData = params.ticket;
        var originStoryId = params.originStoryId;

        var stories = this.model.get('stories');
        var originStory = stories.get(originStoryId);
        var newStory = stories.get(ticketData.story);

        var ticket = originStory.get('tickets').get(ticketData.id);
        originStory.get('tickets').remove(ticket);

        ticket.set('story', newStory.id);
        ticket.set('status', ticketData.status);

        newStory.get('tickets').add(ticket);


    },

    /**
     * Handle ticket change from a different user
     */
    onRemoteTicketChange: function(ticketData) {
        var ticketId = ticketData.id;
        var storyId = ticketData.story;

        var stories = this.model.get('stories');
        var story = stories.get(storyId);
        var ticket = story.get('tickets').get(ticketId);
        ticket.set(ticketData);
    },

    /**
     * Handle ticket created by a different user
     */
    onRemoteTicketCreate: function(params) {
        var ticket = new itsallagile.Model.Ticket(params.ticket);
        
        var stories = this.model.get('stories');
        var story = stories.get(params.storyId);
        story.get('tickets').add(ticket);
    },

    /**
     * Handle ticket deleted by a different user
     */
    onRemoteTicketDelete: function(params) {
        var ticketId = params.ticket.id;
        var storyId = params.storyId;

        var stories = this.model.get('stories');
        var story = stories.get(storyId);
        var ticket = story.get('tickets').get(ticketId);
        story.get('tickets').remove(ticket);
    },

    /**
     * Handle new story added by a different user
     */
    onRemoteStoryAdd: function(storyData) {
        var story = new itsallagile.Model.Story(storyData);
        story.set('tickets', new itsallagile.Collection.Tickets());
        this.model.get('stories').add(story);
    },

    /**
     * Handle story deleted by a different user
     */
    onRemoteStoryDelete: function(storyId) {
        this.model.get('stories').get(storyId).destroy();
    },

    /**
     * Handle story update by different user
     */
    onRemoteStoryUpdate: function(storyDetails) {
        var story = this.model.get('stories').get(storyDetails.id);
        story.set({
            'content': storyDetails.content,
            'points': storyDetails.points,
            'status': storyDetails.status
        });
    }

});



