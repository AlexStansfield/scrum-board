/**
 * View for the board points
 */
itsallagile.View.BoardPoints = Backbone.View.extend({
    tagName: 'div',
    id: 'boardPoints',
    model: null,
    template: "<h3>Board Points: <%= pointsCommitted %> committed, <%= pointsCompleted %> completed</h3>",

    initialize: function(options) {
        this.model = options.model;
    },

    render: function() {
        var storyPoints = this.model.getStoryPoints();
        this.$el.html(_.template(
            this.template, {pointsCommitted : storyPoints.pointsCommitted, pointsCompleted: storyPoints.pointsCompleted}
        ));
        return this;
    }
});