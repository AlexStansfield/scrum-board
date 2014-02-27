/**
 * Model for Boards
 */
itsallagile.Model.Board = Backbone.Model.extend({
    urlRoot: '/api/boards',

    defaults: {
        stories: null,
        chat_messages: null
    },

    initialize: function(options) {

    },

    /**
     * When we get a response from the server, that contains tickets and stories
     * Put those into collections
     */
    parse: function(response) {
        response.stories = new itsallagile.Collection.Stories(response.stories);      
        response.stories.url = this.urlRoot + '/' + response.id + '/stories';
        response.stories.forEach(function(story) {
            var tickets = story.get('tickets');
            story.set('tickets', new itsallagile.Collection.Tickets(tickets));
            story.get('tickets').url = response.stories.url + '/' + story.get('id') + '/tickets';
        });

        response.chat_messages = new itsallagile.Collection.ChatMessages(response.chat_messages);
        response.chat_messages.url = this.urlRoot + '/' + response.id + '/messages';
        return response;
    },

    getStoryPoints: function() {
        var pointsCommitted = 0;
        var pointsCompleted = 0;

        this.get('stories').forEach(function(story) {
            pointsCommitted = pointsCommitted + parseInt(story.get('points'));
            if (story.get('status') == 'done') {
                pointsCompleted = pointsCompleted + parseInt(story.get('points'));
            }
        });

        return {'pointsCommitted': pointsCommitted, 'pointsCompleted': pointsCompleted};
    }
});

