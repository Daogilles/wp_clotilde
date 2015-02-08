/**
 */
(function (w) {
    w.CLO.views = w.CLO.views || {};

    w.CLO.views.Home = CLO.abstract.APageView.extend({
    	events : {
            'mouseover .category' : '_over',
            'mouseout .category' : '_out'
        },
        initialize : function(params) {
            CLO.abstract.APageView.prototype.initialize.call(this, params);
        },
        _over : function (e){
        	TweenLite.to($(e.currentTarget).find('.category-content-bg-visible'), 0.3, {opacity:'1', ease:Linear.ease});
        },
        _out : function (e){
        	TweenLite.to($(e.currentTarget).find('.category-content-bg-visible'), 0.3, {opacity:0, ease:Linear.ease});
        }
    });
})(window);