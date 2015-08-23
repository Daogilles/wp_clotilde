/**
 */
(function (w) {
    w.CLO.views = w.CLO.views || {};

    w.CLO.views.Services = CLO.abstract.APageView.extend({
    	$imgWrapper: $('.services-img-wrap'),
        initialize : function(params) {
        	var scope = this;
            CLO.abstract.APageView.prototype.initialize.call(this, params);            

            this.initSlider();
        },
        initSlider : function() {
            $(document).on('click touchstart','.service-text-next', function(e){
                e.preventDefault();
                var step = $(this).attr('data-next');
                var nextStep = parseInt(step);
                var nextNextStep = parseInt(step)+1;

                $(this).parent().parent().find('.slide.active').removeClass('active').addClass('prev');
                $(this).parent().parent().find('.item'+nextStep).addClass('active').removeClass('next');
                $(this).parent().parent().find('.item'+nextNextStep).addClass('next');
            });

            $(document).on('click touchstart','.service-text-prev', function(e){
                e.preventDefault();
                var step = $(this).attr('data-prev');
                var prevStep = parseInt(step);
                var prevPrevStep = parseInt(step)-1;
                
                $(this).parent().parent().find('.slide.active').removeClass('active').addClass('next');
                $(this).parent().parent().find('.item'+prevStep).addClass('active').removeClass('prev');
                $(this).parent().parent().find('.item'+prevPrevStep).addClass('prev');       
            });
    
        },
        show : function(){
            // var $high = document.querySelector('#highlight');
            // $high.style.height = window.innerHeight+'px';
            
            // var $highImg = document.querySelector('#highlight-img');
            // $highImg.style.marginLeft = -($highImg.offsetWidth/2) +'px';
        }
    });
})(window);
