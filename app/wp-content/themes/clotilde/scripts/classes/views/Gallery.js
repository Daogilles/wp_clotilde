/**
 */
(function (w) {
    w.CLO.views = w.CLO.views || {};

    w.CLO.views.Gallery = CLO.abstract.APageView.extend({
    	msnry: null,
    	// events : {
     //        'mouseover .btn-gallery' : '_over',
     //        'mouseout .btn-gallery' : '_out',
     //        'click .btn-gallery' : '_clickOpen',
     //        'click .close-gallery' : '_clickClose',
     //    },
        initialize : function(params) {
            CLO.abstract.APageView.prototype.initialize.call(this, params);
            // this.$el.on('destroyed', this.removeHandler);

            $(document).on('mouseover', '.btn-gallery', function(e){
            	TweenLite.to($(this).find('.open-gallery.state-hover'), 0.3, {opacity:1, ease:Linear.ease});
            });

            $(document).on('mouseout', '.btn-gallery', function(e){
            	TweenLite.to($(this).find('.open-gallery.state-hover'), 0.3, {opacity:0, ease:Linear.ease});
            });

            $(document).on('click', '.btn-gallery', function(e){
            	console.log($('.btn-gallery'));
	        	$('.gallery-wrapper').addClass('show');
	        	container = document.querySelector('.gallery-container');
	            console.log(container)
				msnry = new Masonry( container, {
					itemSelector: '.gallery-img'
				} );

				setTimeout(function(){
					var length = $('.gallery-img').length;
		            for (var i = 1; i <= length; i++) {
		                setTimeout(function(x) {
		                    return function() { 
		                        $('.gallery-img:nth-child('+x+')').addClass('visible');
		                    };                         
		                }(i), i*100);
		            };
				},300);
            });

            $(document).on('click', '.close-gallery', function(e){
            	$('.gallery-wrapper').removeClass('show');
        		$('.gallery-img').removeClass('visible');
            });
        },
		// removeHandler : function(){
		// 	console.log('View has been destroyed, do we need to stop any Timer?');
		// },
  //       _over : function(e) {        	
  //       	console.log('over')
  //       	TweenLite.to($(e.currentTarget).find('.open-gallery.state-hover'), 0.3, {opacity:1, ease:Linear.ease});
  //       },
  //       _out : function(e) {        	
  //       	TweenLite.to($(e.currentTarget).find('.open-gallery.state-hover'), 0.3, {opacity:0, ease:Linear.ease});
  //       },
  //       _clickOpen : function(e) {
  //       	var scope = this;
  //       	console.log($('.btn-gallery'));
  //       	$('.gallery-wrapper').addClass('show');
  //       	container = document.querySelector('.gallery-container');
  //           console.log(container)
		// 	msnry = new Masonry( container, {
		// 		itemSelector: '.gallery-img'
		// 	} );

		// 	setTimeout(function(){
		// 		var length = $('.gallery-img').length;
	 //            for (var i = 1; i <= length; i++) {
	 //                setTimeout(function(x) {
	 //                    return function() { 
	 //                        $('.gallery-img:nth-child('+x+')').addClass('visible');
	 //                    };                         
	 //                }(i), i*100);
	 //            };
		// 	},300)
			
  //       },
  //       _clickClose : function(e) {
  //       	$('.gallery-wrapper').removeClass('show');
  //       	$('.gallery-img').removeClass('visible');
  //       }
    });
})(window);