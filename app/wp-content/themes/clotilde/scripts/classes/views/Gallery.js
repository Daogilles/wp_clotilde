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

        // $(document).on('mouseover', '.btn-gallery', function(e){
        //     TweenLite.to($(this).find('.open-gallery.state-hover'), 0.3, {opacity:1, ease:Linear.ease});
        // });

        // $(document).on('mouseout', '.btn-gallery', function(e){
        //     TweenLite.to($(this).find('.open-gallery.state-hover'), 0.3, {opacity:0, ease:Linear.ease});
        // });

        // $(document).on('click touchstart', '.btn-gallery', function(e){            
        //     $('.gallery-wrapper').addClass('show');

            
        // });

        // $(document).on('click touchstart', '.close-gallery', function(e){
        //     setTimeout(function(){
        //         var length = $('.gallery-img').length;
        //             for (var i = 1; i <= length; i++) {
        //                 setTimeout(function(x) {
        //                     return function() { 
        //                         $('.gallery-img:nth-child('+x+')').removeClass('visible');
        //                         if (x == length) {
        //                           setTimeout(function(){
        //                               $('.gallery-wrapper').removeClass('show');
        //                           },100);
                                    
        //                         }
        //                     };                         
        //                 }(i), i*100);
        //             };
        //     },300);
        // });
    },
    show : function(callback) {
        var scope = this;
        $('.gallery-img-inner').on('click', function(){
            $('body').addClass('full');
            setTimeout(function(){
              $('.gallery-zoom').addClass('show');              
              
            },100);
            scope.slider(parseInt($(this).attr('data-item')));
        });

        var $high = document.querySelector('#highlight');
        $high.style.height = window.innerHeight+'px';
        
        var $galZoom = document.querySelector('.gallery-zoom');
        $galZoom.style.lineHeight = window.innerHeight+'px';

        var $galleryWrapper = document.querySelector('.gallery-wrapper');
        $galleryWrapper.style.top = (window.innerHeight+43)+'px';

        container = document.querySelector('.gallery-container');
        msnry = new Masonry( container, {
            itemSelector: '.gallery-img'
        } );

        // setTimeout(function(){
        //     var length = $('.gallery-img').length;
        //         for (var i = 1; i <= length; i++) {
        //             setTimeout(function(x) {
        //                 return function() { 
        //                     $('.gallery-img:nth-child('+x+')').addClass('visible');
        //                 };                         
        //             }(i), i*100);
        //         };
        // },1000);
    },
    slider : function(startSlide){
      var scope = this;
      var wTotal = 0;
      var $sliderWrapper = document.querySelector('.gallery-zoom-img');
      var $imgAll = document.querySelectorAll('.gallery-zoom-img img');
      var $aRight = document.querySelector('.gallery-arrow-right');
      var $aLeft = document.querySelector('.gallery-arrow-left');
      var imgWidthArray = [];
      var calcTranslate = 0;

      for (var i = 0; i < $imgAll.length; i++) {
        wTotal += $imgAll[i].offsetWidth + 50;
        imgWidthArray[i] = $imgAll[i].offsetWidth;
      };
      $sliderWrapper.style.width = wTotal + 'px';
      calcTranslate = imgWidthArray[startSlide]/2;
      
      TweenLite.set($sliderWrapper, {x: -(imgWidthArray[startSlide]/2)});

      if (startSlide != ($imgAll.length-1) ) {
        TweenLite.set($aRight, {x: imgWidthArray[startSlide]/2});
      }      
      if(startSlide != 0) {
        TweenLite.set($aLeft, {x: -(imgWidthArray[startSlide]/2)});  
      }      
      console.log(calcTranslate);

      $aRight.addEventListener('click',function(){
        calcTranslate += imgWidthArray[parseInt(startSlide)+1]/2;
        TweenLite.to($sliderWrapper, 0.6, {x: -calcTranslate, ease:Power3.easeOut})
      });

    }
    });
})(window);