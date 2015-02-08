/**
 */
(function(w) {
    w.CLO.abstract = w.CLO.abstract || {};

    var APageView = Backbone.View.extend({
        tagName : 'div',
        'className' : 'page-item',
        isRender : false,
        key : '',
        model : null,
        initialize : function(params) {

            return this;
        },
        load : function() {

            return this;
        },
        ready : function() {

            return this;
        },
        render : function() {
            this.$el.html(this.model.get('content'));
            return this;
        },
        show : function(callback) {
            callback && callback();
            return this;
        },
        hide : function(callback) {
            callback && callback();
            return this;
        }
    });

    w.CLO.abstract.APageView = APageView;
})(window);