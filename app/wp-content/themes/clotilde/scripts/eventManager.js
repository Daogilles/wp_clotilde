/**
 * module de gestion des évènements utilisateur
 */
/**
 * Constructeur du module
 */
CLO.EventManager = function () {
    /**
     * Variable privée au constructeur
     */
    var _pager = $('#pagination li');
    var base = this;
    var performAnimation = false;
    var touch = {
        currentPos: 0
    };

    /**
     *  Options du module
     */
    this.options = {
        foo: true
    };

    

    /**
     * Initialisation du module
     */
    this.initialize = function () {
        
        // Ajout ecouteur au scroll de la souris
        switch (CLO.config.device) {
            case 'mobile':
            case 'tablet':
                
                document.addEventListener(
                    'touchstart',
                    base.onTouchStart,
                    false
                );

                document.addEventListener(
                    'touchmove',
                    base.onTouchMove,
                    false
                );

                document.addEventListener(
                    'touchend',
                    base.onTouchEnd,
                    false
                );

                break;

            default:
                if (!document.addEventListener) {
                    document.attachEvent(
                        'onmousewheel',
                        base.onMouseWheelEvent,
                        false
                    )
                } else {

                    document.addEventListener(
                        'mousewheel',
                        base.onMouseWheelEvent,
                        false
                    );
                    
                    // Event for firefox
                    // $('#wrapper')[0].addEventListener(
                    //     'DOMMouseScroll',
                    //     base.onMouseWheelEvent,
                    //     false
                    // );
                    $('html').on('keydown',function(e){
                        if(e.keyCode == 38) { // up
                            base.scrollContentTo(1);
                        }
                        else if(e.keyCode == 40) { // down
                            base.scrollContentTo(-1);
                        }
                    });

                }

                break;
        }
        
        // event onClick
        base.addClickEvent();

        CLO.$items = $('.scroll').each(function(index) {
            index > 0 && TweenMax.set(this, {y : CLO.config.screen.height})
        });

        CLO.currentItem = 0;
        CLO.config.activeItem = 0;

    };
    
    /**
     * Ajout click event sur les boutons
     */
    
    this.addClickEvent = function() {

        // Scroll bottom
        $('.arrow-down, .scrollNext').click(function(e) {
            CLO.config.activeItem++;
            base.animateContent(CLO.config.activeItem);
            return false;
        });

        // $('#pagination li').each(function(_index) {
        //     $(this).find('a').click(function(e) {
        //         e.preventDefault();
        //         CLO.config.activeItem = _index;
        //         console.log(_index);
        //         base.animateContent( _index );
        //         return false;
        //     });
        // });
    }
    


    /**
     * Scroll à partir du souris
     */
    this.onMouseWheelEvent = function (_event) {
        // @see http://www.sitepoint.com/html5-javascript-mouse-wheel/
        var event = window.event || _event; // old IE support
        var direction = Math.max(-1, Math.min(1, (event.wheelDelta || -event.detail)));
        base.scrollContentTo(direction);
    };

    /**
     * Touch start
     */
    this.onTouchStart = function (_event) {
        if (_event.target.tagName.toLowerCase() === 'a') {
            return false;
        }

        _event.preventDefault();
        _event.stopPropagation();

        touch.currentPos = _event.touches[0].pageY;
    };

    /**
     * Touch move
     */
    this.onTouchMove = function (_event) {
        var currentPosition = _event.touches[0].pageY;

        if (performAnimation) {
            return false;
        }

        if (currentPosition > touch.currentPos) {
            if (CLO.config.activeItem !== 0) {
                CLO.config.activeItem --;
                performAnimation = true;
                base.animateContent(CLO.config.activeItem);
            }
        } else {
            if (CLO.config.activeItem < (CLO.config.itemSize - 1)) {
                CLO.config.activeItem ++;
                performAnimation = true;
                base.animateContent(CLO.config.activeItem);
            }
        }
    };


    /**
     * Touch end
     */
    this.onTouchEnd = function (_event) {
    }


    /**
     * Recuperation de l'index du contenu à afficher et lancement du defilement
     */
    this.scrollContentTo = function (_direction) {
        if (performAnimation) {
            return;
        }

        switch (_direction) {
            // move up
            case 1:
                if (CLO.config.activeItem === 0) {
                    performAnimation = false;
                    return;
                }

                CLO.config.activeItem --;
                break;
            // move down
            case -1:
                if (CLO.config.activeItem === (CLO.config.itemSize - 1)) {
                    performAnimation = false;
                    return;
                }
                CLO.config.activeItem ++;
                break;

            default:
                break;
        }

        this.animateContent(CLO.config.activeItem);
    };

    /**
     * Defilement du contenu
     */
    this.animateContent = function (_item) {
        var scope = this,
            duration = 0.5,
            duration2 = 0.8;

        // if( !($('html').hasClass('touch')) ){
        //     if(performAnimation) return;
        // }
        // console.log(CLO.$items)
        // console.log(_item)
        // console.log(CLO.currentItem)
        if(_item > CLO.currentItem){
            performAnimation = true;

            TweenLite.set(CLO.$items[_item], {display : 'block', scaleX : 1, scaleY : 1, opacity : 1, y : CLO.config.screen.height});
            TweenLite.to(CLO.$items[_item], duration2, {y : 0, ease: Power4.easeInOut, onComplete : _finish});
            TweenLite.to(CLO.$items[CLO.currentItem], duration, {ease: Power4.easeIn, scaleX : 0.9, scaleY : 0.9, opacity : 0, onComplete : function() {
                TweenLite.set(CLO.$items[CLO.currentItem], {display : 'none'});
            }});
        }else if (_item < CLO.currentItem) {
            performAnimation = true;

            TweenLite.set(CLO.$items[_item], {display : 'block'});

            TweenLite.to(CLO.$items[CLO.currentItem], duration2, {y : CLO.config.screen.height, ease: Power3.easeIn, onComplete : function() {
                var target = this.target;
                setTimeout(function() {
                    TweenLite.set(target, {display : 'none'});    
                }, 300);
            }});

            setTimeout(function() {
                TweenLite.set(CLO.$items[_item], {display : 'block', scaleX : 0.9, scaleY : 0.9, opacity : 0, y : 0});
                TweenLite.to(CLO.$items[_item], duration, {ease: Power4.easeOutIn, scaleX : 1, scaleY : 1, opacity : 1, onComplete :  _finish});
            }, 300);
            
        }

        function _finish() {
            CLO.currentItem = _item;
            // $('#pagination li').removeClass('item-active');
            // $('#pagination li').eq(_item).addClass('item-active');
            var ind = _item + 1;
            $('#pagination div').html(ind+'/'+CLO.config.itemSize)
            setTimeout(function() {
                performAnimation = false;
                // TweenLite.set($block, {display: 'none'});   
            }, 1000);
        }

        return;
    }
    

};
