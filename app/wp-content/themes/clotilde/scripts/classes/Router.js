/**
 */
(function (w) {

    var Router = Backbone.Router.extend({
        routes : {
            '*actions' : 'all'
        },
        all : function() {
            var arg = Array.prototype.slice.call(arguments, 0);
            this.trigger('GO_TO', arg.join(''));
        }
    });

    w.CLO.Router = Router;



})(window);