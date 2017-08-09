/**
 */
(function (w) {
    w.CLO.views = w.CLO.views || {};

    w.CLO.views.Gallery = CLO.abstract.APageView.extend({
    msnry: null,
    initialize : function(params) {
        CLO.abstract.APageView.prototype.initialize.call(this, params);
    },
    show : function(callback) {
        var scope = this;
        var $aRight = document.querySelector('.gallery-arrow-right');
        var $h = $(window).height();
        var $w = $(window).width();

        $('.gallery-img-inner').on('click', function(){
            var item = parseInt($(this).attr('data-item'));

            TweenLite.to($('.gallery-zoom'), 0.5, {
                opacity: 1,
                'visibility': 'visible',
                onComplete:function() {
                    console.log('ici')
                    scope.slider(item);
                    // $('body').addClass('full');
                }
            });

        });

        $('.gallery-zoom-close').on('click', function(){
        //   $('body').removeClass('full');
          $('.gallery-zoom-inner').removeClass('show');
          TweenLite.to($('.gallery-zoom'), 0.4, {
            opacity: 0,
            'visibility': 'hidden',
            onComplete: function(){
              $('.gallery-zoom-img').removeAttr('style');
            }
          });
        })

        var $high = document.querySelector('#highlight');
        $high.style.height = window.innerHeight+2+'px';

        var $galZoom = document.querySelector('.gallery-zoom');
        $galZoom.style.lineHeight = window.innerHeight+'px';

        var $galleryWrapper = document.querySelector('.gallery-wrapper');
        $galleryWrapper.style.top = (window.innerHeight+43)+'px';

        container = document.querySelector('.gallery-container');
        msnry = new Masonry( container, {
            itemSelector: '.gallery-img'
        } );

        $(document).on('resize', function() {
          console.log('in')
          msnry.layout();
        });

    },
    slider : function(startSlide){
        var scope = this;
        var wTotal = 0;
        var $sliderWrapper = document.querySelector('.gallery-zoom-img');
        var $imgAll = document.querySelectorAll('.gallery-zoom-img img');
        var $arrows = document.querySelector('.gallery-arrow');
        var $aRight = document.querySelector('.gallery-arrow-right');
        var $aLeft = document.querySelector('.gallery-arrow-left');

        var $descrWrapper = document.querySelector('.gallery-zoom-descr');

        var imgWidthArray = [];
        var calcTranslate = 0;
        var inTransition = false;
        var $h = $(window).height();
        var $w = $(window).width();
        var isPortrait = $h>$w;
        var maxWidthSet = Math.floor($w*0.8);

        for (var i = 0; i < $imgAll.length; i++) {
          wTotal += $imgAll[i].offsetWidth + 100;
          var w = $imgAll[i].offsetWidth;
          var h = $imgAll[i].offsetHeight;
          $imgAll[i].classList.remove('active');
          $imgAll[i].style.opacity = 0.1;

          if (w > h) {
            $imgAll[i].classList.add('landscape');
            if (isPortrait) {
              $imgAll[i].style.maxWidth = maxWidthSet+'px';
              $imgAll[i].style.height = 'auto';
              imgWidthArray[i] = maxWidthSet;
              w = maxWidthSet;
            } else {
              imgWidthArray[i] = w;
            }
          }else {
            $imgAll[i].classList.add('portrait');
            imgWidthArray[i] = w;
          }

          $('.gallery-zoom-descr span[data-item='+i+']').css({width:w});

          // calculate position on init
          if ( i < startSlide+1 ) {
              if ( i == startSlide ) {
                calcTranslate += w/2;
              }else {
                calcTranslate += w + 50;
              }
          }
        };

        $sliderWrapper.style.width = wTotal + 'px';
        $descrWrapper.style.width = wTotal + 'px';
        console.log(imgWidthArray);

        // Display slider to right position in init
        TweenLite.set($sliderWrapper, {x: -calcTranslate});
        TweenLite.set($descrWrapper, {x: -calcTranslate});

        // Check if first/last slide
        if (startSlide != ($imgAll.length-1) ) {
          TweenLite.set($aRight, {x: imgWidthArray[startSlide]/2, display:'block', opacity:1});
        }
        if(startSlide != 0) {
          // 70 = size div PREV + 15 margin
          TweenLite.set($aLeft, {x: -(imgWidthArray[startSlide]/2) - 65, display:'block', opacity:1});
        }else {
          TweenLite.set($aLeft, {opacity:0});
        }

        $('.gallery-zoom-inner').addClass('show');
        document.querySelector('.gallery-zoom-img img[data-item="'+startSlide+'"]').classList.add('active');
        TweenLite.set(document.querySelector('.gallery-zoom-img img[data-item="'+startSlide+'"]'), {
          opacity:1
        });

        $('.gallery-arrow-right').on('click touchstart', function(){
        // $aRight.addEventListener('click',function(){

            if(!inTransition){
                var scope = this;
                inTransition = true;
                // Select Active
                var currentSlideItem = document.querySelector('.gallery-zoom-img img.active');
                var currentSlide = parseInt(currentSlideItem.dataset.item);

                TweenLite.set($aLeft, {opacity:0});
                TweenLite.set($aRight, {opacity:0});
                TweenLite.set($descrWrapper, {opacity:0});


                // CALC = half of active img + half img next + margin
                calcTranslate += (imgWidthArray[currentSlide]/2) + (imgWidthArray[currentSlide+1]/2) + 50;

                TweenLite.to($sliderWrapper, 0.6, {x: -calcTranslate, ease:Power3.easeOut, onComplete:function(){
                    inTransition = false;
                    currentSlideItem.classList.remove('active');
                    document.querySelector('.gallery-zoom-img img[data-item="'+(currentSlide+1)+'"]').classList.add('active');

                    // Check if first/last slide
                    if ((currentSlide+1) != ($imgAll.length-1) ) {
                      TweenLite.set($aRight, {x: imgWidthArray[currentSlide+1]/2, display:'block'});
                      TweenLite.to($aRight, 0.6, {opacity:1,ease:Power3.ease});
                    }else {
                      TweenLite.set($aRight, {opacity:0, display:'none'});
                    }

                    TweenLite.set($aLeft, {x: -(imgWidthArray[currentSlide+1]/2) - 65, display:'block'});
                    TweenLite.to($aLeft, 0.6, {opacity:1,ease:Power3.ease});

                }});

                TweenLite.to($descrWrapper, 0.6, {x: -calcTranslate, ease:Power3.easeOut, onComplete:function(){
                  TweenLite.to($descrWrapper, 0.5, {opacity:1,ease:Power3.ease});
                }});

                TweenLite.to(document.querySelector('.gallery-zoom-img img[data-item="'+currentSlide+'"]'), 0.4, {
                  opacity:0.3
                });
                TweenLite.to(document.querySelector('.gallery-zoom-img img[data-item="'+(currentSlide+1)+'"]'), 0.6, {
                  opacity:1
                });
            }
        });

        $('.gallery-arrow-left').on('click touchstart', function(){
        // $aLeft.addEventListener('click',function(){

            if(!inTransition){
                var scope = this;
                inTransition = true;
                // Select Active
                var currentSlideItem = document.querySelector('.gallery-zoom-img img.active');
                var currentSlide = parseInt(currentSlideItem.dataset.item);

                TweenLite.set($aLeft, {opacity:0});
                TweenLite.set($aRight, {opacity:0});
                TweenLite.set($descrWrapper, {opacity:0});

                // CALC = half of active img + half img next + margin
                calcTranslate -= (imgWidthArray[currentSlide]/2) + (imgWidthArray[currentSlide-1]/2) + 50;

                TweenLite.to($sliderWrapper, 0.6, {x: -calcTranslate, ease:Power3.easeOut, onComplete:function(){
                    inTransition = false;
                    currentSlideItem.classList.remove('active');
                    document.querySelector('.gallery-zoom-img img[data-item="'+(currentSlide-1)+'"]').classList.add('active');

                    TweenLite.set($aRight, {x: imgWidthArray[currentSlide-1]/2, display:'block'});
                    TweenLite.to($aRight, 0.6, {opacity:1,ease:Power3.ease});

                    if((currentSlide-1) != 0) {
                      // 70 = size div PREV + 15 margin
                      TweenLite.set($aLeft, {x: -(imgWidthArray[currentSlide-1]/2) - 65, display:'block'});
                      TweenLite.to($aLeft, 0.6, {opacity:1,ease:Power3.ease});
                    }else {
                      TweenLite.set($aLeft, {opacity:0, display:'none'});
                    }
                }});
                TweenLite.to($descrWrapper, 0.6, {x: -calcTranslate, ease:Power3.easeOut, onComplete:function(){
                  TweenLite.to($descrWrapper, 0.5, {opacity:1,ease:Power3.ease});
                }});

                TweenLite.to(document.querySelector('.gallery-zoom-img img[data-item="'+currentSlide+'"]'), 0.4, {
                  opacity:0.3
                });
                TweenLite.to(document.querySelector('.gallery-zoom-img img[data-item="'+(currentSlide-1)+'"]'), 0.6, {
                  opacity:1
                });
            }
        });

    }
    });
})(window);
