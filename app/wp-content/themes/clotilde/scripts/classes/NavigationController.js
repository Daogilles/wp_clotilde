/**
 */
(function (w) {

    var NavigationController = Backbone.View.extend({
        $wrapper : $('#page'),
        $contentPage : $('.content-page'),
        $loading : $('#loader'),
        $loadPos : $('#loader-pos'),
        $loadMask : $('#loader-mask'),
        $loadContent : $('#loader-content'),
        layoutManager : null,
        eventManager : null,
        currentPage : null,
        pages : {},
        isWorking : false,
        scrollInit : false,
        startLoading : function() {
            $('html').css({height:'100%',width:'100%', overflow:'hidden'});
            this.$loading.addClass('show');
        },
        endLoading : function() {
            var scope = this;
            $('html').css({overflow:'visible'});
            TweenLite.to(this.$loading, 0.8, {backgroundColor:'rgba(0,0,0,0)', ease:Power4.easeIn,onComplete:function(){
                setTimeout(function(){
                    TweenLite.to(scope.$loadPos, 1, {opacity:0, onComplete: function(){
                        scope.$loading.removeClass('show');
                        scope.$loadMask.removeAttr('style');
                        scope.$loadPos.removeAttr('style');
                        scope.$loadContent.html('');
                        setTimeout(function(){
                            scope.$loading.removeAttr('style');
                        },300)
                        // scope.$loading.css({backgroundColor:'white'});
                    }})
                },200)
            }})
            
        },
        initScroll: function(){
            CLO.config.browser = navigator.userAgent||navigator.vendor||window.opera;
            // Detect device type
            var deviceType = undefined;
            var isMobile = /android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(CLO.config.browser)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i.test(CLO.config.browser.substr(0,4));

            if (isMobile) {
                deviceType = 'mobile';
            } else {

                var supportsTouch = ('ontouchstart' in window || navigator.msMaxTouchPoints) ? true:false;

                if (supportsTouch) {
                    deviceType = 'tablet';
                } else {
                    deviceType = 'desktop';
                }
            }

            CLO.config.device = deviceType;

            CLO.config.activeItem = 0;

            this.layoutManager = new CLO.LayoutManager();
            this.eventManager = new CLO.EventManager(CLO.config.activeItem);

            this.layoutManager.initialize();
            this.eventManager.initialize();
            this.eventManager.scrollContentTo(CLO.config.activeItem);
            this.scrollInit = true;
            
            
        },
        displayPage : function(url) {
            var scope = this;
            if(this.isWorking) return;
            this.isWorking = true;
            var loaderText = url.replace('/', '');            
            // this.$loadContent.css({width:0});

            var page = this.pages[url] || new CLO.abstract.APageModel({url : url});

            this.pages[url] = page;
            if(scope.currentPage) {
                scope.currentPage.view.hide(function() {
                    // var scale = 1;
                    console.log('start loading')
                    scope.startLoading();                    
                    
                    // TweenLite.to(scope.$wrapper, 0.3, {scaleX : scale, scaleY : scale, ease:Power4.easeIn, onComplete : function() {
                    _readyToGoNext();
                    // }});
                });
            } else {
                console.log('ready next')
                scope.startLoading();
                _readyToGoNext();
            }

            function _readyToGoNext() {
                console.log('page.isLoaded  ' + page.isLoaded)
                if(page.isLoaded) {
                    _pageReady();
                } else {
                    _loadPage();
                }
            }

            function _loadPage() {
                page.fetch({
                    error : function() {
                        console.log('error')
                    },
                    success : function() {
                        var className = CLO.PAGES_VIEWS[page.get('pageType')] ? CLO.views[CLO.PAGES_VIEWS[page.get('pageType')]] : CLO.abstract.APageView;
                        var view = new className({
                            model : page,
                            key : url
                        });

                        page.view = view.render();

                        _pageReady();
                    }
                });
            }

            function _pageReady() {
                document.title = page.get('seoTitle');
                setTimeout(function() { 
                    scope.$el.html(page.view.$el);
                    preloadImage()
                }, 300);
            }

            function preloadImage() {
                console.log('in preloadImage')
                var menuRespHeight = $('#menu_resp ul li').height();
                var windowHeight = $(window).height();
                $('#menu_resp').css({lineHeight:windowHeight+'px'})
                $('#menu_resp ul li').css({lineHeight:menuRespHeight+'px'})
                if(loaderText == ''){
                    loaderText = '<img src="'+CLO.CONFIG.THEME_URL+'/img/logo@2x.png" alt="Logo">';
                    $('#loader-content').append(loaderText);
                    $('#loader-pos').css({width: 248})
                    $('#loader-mask').css({width: 248})
                }else{
                    $('#loader-content').append(loaderText);
                    $('#loader-pos').css({width: $('#loader-content').width()})
                    $('#loader-mask').css({width: $('#loader-content').width()})
                }
                var img_to_load = [];

                page.view.$el.find('img').each(function () {
                    if(this.complete) img_to_load.push($(this).attr('src'));
                });

                var loaded_images = 0;
                var total_images = img_to_load.length;
                console.log(total_images, img_to_load)
                if(total_images == img_to_load.length) {
                    if(total_images == 0){
                        TweenLite.to(scope.$loadMask, 1, { marginLeft: '100%', ease : Power4.easeOut, onComplete: function(){
                            scope.$loadMask.css({width:0});
                            imageReady();
                        } });
                    }else{
                        for (var i = 0; i < total_images; i++) {
                            var img = new Image();
                            img.src = img_to_load[i];
                            img.onload = function () {
                                loaded_images++;
                                var progress = Math.round(100 * loaded_images / total_images)+'%';
                                TweenLite.to(scope.$loadMask, 1, { marginLeft: progress, ease : Power4.easeOut, onComplete: function(){
                                    scope.$loadMask.css({width:0});
                                    if (loaded_images == img_to_load.length ){
                                        imageReady();
                                    } 
                                } });
                                // TweenLite.to(scope.$loadMask, 10, { width: 0, ease : Power4.easeOut, onComplete: function(){
                                    
                                // }})
                            }
                            img.onerror = function(){
                                loaded_images++;
                            }
                        }
                    }
                    
                } else {
                    imageReady();
                }

            };

            function imageReady() {
                $h = $(window).height();
                $('.full-vertical').css({height: $h, position:'relative'});
                $('div.scroll').css({lineHeight: $h+'px'});
                CLO.config.itemSize = $('div.scroll').length;
                // var paginationLength = $('#pagination l').length;
                // $('#pagination').css({height:(paginationLength*80)+'px', marginTop: -(paginationLength*80/2) })
                
                // if(loaderText == 'beauty' || loaderText == 'fashion' || loaderText == 'celebrities' || loaderText == 'body-art' || loaderText == 'services'){
                if(loaderText == 'beauty' || loaderText == 'fashion' || loaderText == 'celebrities' || loaderText == 'body-art'){
                    if(!scope.scrollInit){
                        scope.initScroll();
                    }else{
                        

                        $('#pagination li').each(function(_index) {
                            $(this).find('a').click(function(e) {
                                e.preventDefault();
                                e.stopPropagation();
                                CLO.config.activeItem = _index;
                                scope.eventManager.animateContent( _index );
                            });
                        });

                        // CLO.$items = $('.scroll').each(function(index) {
                        //     index > 0 && TweenMax.set(this, {y : CLO.config.screen.height})
                        // });
                        // CLO.currentItem = 0;
                        // CLO.config.activeItem = 0;
                        scope.eventManager.initialize();
                        TweenLite.set(CLO.$items[0], {display : 'block', scaleX : 1, scaleY : 1, opacity : 1, y : 0});

                    }
                }

                if(loaderText == 'services') {
                    $('.service-article .service-img-wrapper.slider-container').slick({
                        infinite:true,
                        arrows:false,
                        autoplay: true,
                        pauseOnHover: false,
                        dots: true,
                        autoplaySpeed: 8000
                    });
                    // $('.service-article:nth-child(even) .slider-container').slick({
                    //     infinite:true,
                    //     arrows:false,
                    //     autoplay: true,
                    //     pauseOnHover: false
                    // });
                    // $('.service-article:nth-child(even) .slider-container').bxSlider({
                    //     infiniteLoop:true,
                    //     controls:false,
                    //     auto: true
                    // });
                }
                
                scope.endLoading();

                scope.currentPage = page;
                // scope.$contentPage.show();
                page.view.show(function() {
                    setTimeout(function() {
                        scope.isWorking = false;
                    }, 100);
                });
            }
        }
    });

    w.CLO.NavigationController = NavigationController;

})(window);