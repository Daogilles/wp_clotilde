/**
 */
(function (w) {
    w.CLO.views = w.CLO.views || {};

    w.CLO.views.Services = CLO.abstract.APageView.extend({
    	$imgWrapper: $('.services-img-wrap'),
        initialize : function(params) {
        	var scope = $(this);
            CLO.abstract.APageView.prototype.initialize.call(this, params);
            // console.log('Services view');
            this.$imgWrapper.each(function(){
            	if($(this).find('.services-img').length > 1){
            		$(this).find('.services-img').css({border:'2px solid red'});
            		// scope.css({background:'red'});
            	}
            })
        }
    });
})(window);