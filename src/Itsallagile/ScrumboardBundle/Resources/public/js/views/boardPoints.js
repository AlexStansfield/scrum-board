/**
 * View for the board points
 */
itsallagile.View.BoardPoints = Backbone.View.extend({
    tagName: 'div',
    id: 'boardPoints',
    model: null,
    template: "<h3>Board Points: <%= committed %> committed, <%= completed %> completed</h3>",

    initialize: function(options) {
        this.model = options.model;
        //this.model.get('stories').bind('change sync destroy', this.render);
    },

    render: function() {
        var storyPoints = this.model.getStoryPoints();
        this.$el.html(_.template(
            this.template, {committed : storyPoints.committed, completed: storyPoints.completed}
        ));
        return this;
    }
});