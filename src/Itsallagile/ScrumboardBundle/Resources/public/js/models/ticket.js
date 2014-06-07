/**
 * Tickets Model
 */
itsallagile.Model.Ticket = Backbone.Model.extend({
    defaults: {
        content: ''
    },

    /**
     * Get the age of a ticket in days 
     */
    getAge: function() {
        var now = new Date();        
        var last = new Date(this.get("modified"));
        var age = Math.floor((now.getTime() - last.getTime()) / (1000 * 60 * 60 * 24));
        return age >= 0 ? age : 0;
    },

    /**
     * Get the initials of the assigned user
     */
    getAssignedUserInitials: function() {
        var user = this.get("assigned_user");

        if (user === undefined) {
            return '';
        }

        return user.name.split(' ').map(function (s) { return s.charAt(0); }).join('');
    },

    /**
     * Get the ID of the assigned user
     */
    getAssignedUserId: function() {
        var user = this.get("assigned_user");

        if (user === undefined) {
            return '';
        }

        return user.id;
    }
});

