/**
 */
(function (w) {
    w.CLO.views = w.CLO.views || {};

    w.CLO.views.Gallery = CLO.abstract.APageView.extend({
        initialize : function(params) {
            CLO.abstract.APageView.prototype.initialize.call(this, params);

        }
    });
})(window);