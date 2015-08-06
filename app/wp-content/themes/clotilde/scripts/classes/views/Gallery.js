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
        var $aRight = document.querySelector('.gallery-arrow-right');

        $('.gallery-img-inner').on('click', function(){
            $('body').addClass('full');
            $('.gallery-zoom').addClass('show'); 
            setTimeout(function(){
              $('.gallery-zoom-inner').addClass('show');
            },600);
            scope.slider(parseInt($(this).attr('data-item')));
        });

        $('.gallery-zoom-close').on('click', function(){
          $('body').removeClass('full');          
          $('.gallery-zoom-inner').removeClass('show');
          setTimeout(function(){
            $('.gallery-zoom').removeClass('show');
            $('.gallery-zoom-img').removeAttr('style');
          }, 600);
        })

        

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

      document.querySelector('.gallery-zoom-img img[data-item="'+startSlide+'"]').classList.add('active');

      for (var i = 0; i < $imgAll.length; i++) {
        wTotal += $imgAll[i].offsetWidth + 100;
        var w = $imgAll[i].offsetWidth;
        var h = $imgAll[i].offsetHeight;
        imgWidthArray[i] = w;

        if (w > h) {
          $imgAll[i].classList.add('landscape');
        }else {
          $imgAll[i].classList.add('portrait');          
        }
        
        // calculate position on init
        if ( i < startSlide+1 ) {
          console.log(i, startSlide)
            if ( i == startSlide ) {
              calcTranslate += w/2;
            }else {
              calcTranslate += w + 50;
            }                 
        }
      };

      $sliderWrapper.style.width = wTotal + 'px';
      console.log(imgWidthArray);      
      
      // Display slider to right position in init
      TweenLite.set($sliderWrapper, {x: -calcTranslate});

      // Check if first/last slide
      if (startSlide != ($imgAll.length-1) ) {
        TweenLite.set($aRight, {x: imgWidthArray[startSlide]/2});
      }      
      if(startSlide != 0) {
        TweenLite.set($aLeft, {x: -(imgWidthArray[startSlide]/2)});  
      }            

      $aRight.addEventListener('click',function(){
          // Select Active
          var currentSlideItem = document.querySelector('.gallery-zoom-img img.active');
          var currentSlide = parseInt(currentSlideItem.dataset.item);
          // CALC = half of active img + half img next + margin
          calcTranslate += (imgWidthArray[currentSlide]/2) + (imgWidthArray[currentSlide+1]/2) + 50;

          TweenLite.to($sliderWrapper, 0.6, {x: -calcTranslate, ease:Power3.easeOut});
          currentSlideItem.classList.remove('active');
          document.querySelector('.gallery-zoom-img img[data-item="'+(currentSlide+1)+'"]').classList.add('active');     
        });
    }
    });
})(window);